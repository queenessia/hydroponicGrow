<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SharingController extends Controller
{
    public function index()
    {
        return view('sharing.index');
    }

    public function getPosts(Request $request)
    {
        try {
            $memberId = Auth::guard('member')->id();
            
            // Get all posts (main posts and replies) in chronological order
            // But organize them so replies appear under their parent posts
            $allPosts = Post::with(['member', 'parent.member'])
                ->withCount(['likes', 'replies'])
                ->latest()
                ->get();

            // Add default profile icon for each post's member
            $allPosts->each(function ($post) {
                if ($post->member) {
                    $post->member->profile_icon = $this->getDefaultProfileIcon();
                    $post->member->display_name = $post->member->username ?? 'Anonymous';
                }
                
                // Also add for parent post member if it's a reply
                if ($post->parent && $post->parent->member) {
                    $post->parent->member->profile_icon = $this->getDefaultProfileIcon();
                    $post->parent->member->display_name = $post->parent->member->username ?? 'Anonymous';
                }
            });

            // Separate main posts and replies
            $mainPosts = $allPosts->whereNull('parent_id');
            $replies = $allPosts->whereNotNull('parent_id')->groupBy('parent_id');

            // Build the organized post structure
            $organizedPosts = collect();

            foreach ($mainPosts as $mainPost) {
                // Add interaction flags for authenticated members
                if ($memberId) {
                    $mainPost->is_liked = $mainPost->likes()->where('member_id', $memberId)->exists();
                    $mainPost->is_bookmarked = $mainPost->bookmarks()->where('member_id', $memberId)->exists();
                } else {
                    $mainPost->is_liked = false;
                    $mainPost->is_bookmarked = false;
                }

                $organizedPosts->push($mainPost);

                // Add replies for this main post
                if ($replies->has($mainPost->id)) {
                    foreach ($replies[$mainPost->id] as $reply) {
                        if ($memberId) {
                            $reply->is_liked = $reply->likes()->where('member_id', $memberId)->exists();
                            $reply->is_bookmarked = $reply->bookmarks()->where('member_id', $memberId)->exists();
                        } else {
                            $reply->is_liked = false;
                            $reply->is_bookmarked = false;
                        }
                        $organizedPosts->push($reply);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'posts' => $organizedPosts->values()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in getPosts: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error loading posts: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all users/members for display (read-only)
     */
    public function getUsers(Request $request)
    {
        try {
            $users = Member::select('id', 'username', 'email', 'created_at')
                ->latest()
                ->get();

            // Add default profile icon for each user
            $users->each(function ($user) {
                $user->profile_icon = $this->getDefaultProfileIcon();
                $user->display_name = $user->username ?? 'Anonymous';
            });

            return response()->json([
                'success' => true,
                'users' => $users
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in getUsers: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error loading users: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update only username for current authenticated user
     * This is the only "post" operation allowed - just for updating username
     */
    public function updateUsername(Request $request)
    {
        try {
            // Check if user is authenticated
            if (!Auth::guard('member')->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }

            // Validate input
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:50|unique:members,username,' . Auth::guard('member')->id()
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update only username
            $member = Auth::guard('member')->user();
            $member->username = $request->username;
            $member->save();

            return response()->json([
                'success' => true,
                'message' => 'Username updated successfully',
                'user' => [
                    'id' => $member->id,
                    'username' => $member->username,
                    'display_name' => $member->username,
                    'profile_icon' => $this->getDefaultProfileIcon()
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in updateUsername: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating username: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current authenticated user profile
     */
    public function getCurrentUser()
    {
        try {
            if (!Auth::guard('member')->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not authenticated'
                ], 401);
            }

            $member = Auth::guard('member')->user();
            
            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $member->id,
                    'username' => $member->username,
                    'email' => $member->email,
                    'display_name' => $member->username ?? 'Anonymous',
                    'profile_icon' => $this->getDefaultProfileIcon(),
                    'created_at' => $member->created_at
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in getCurrentUser: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error loading user profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get default profile icon (can be customized)
     */
    private function getDefaultProfileIcon()
    {
        // You can customize this to return different default icons
        return asset('images/default-avatar.png'); // or use a base64 encoded default icon
        
        // Alternative: Return a data URL for a simple SVG icon
        // return 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"><circle cx="20" cy="20" r="20" fill="#e5e7eb"/><circle cx="20" cy="15" r="6" fill="#9ca3af"/><path d="M8 32c0-6.627 5.373-12 12-12s12 5.373 12 12" fill="#9ca3af"/></svg>');
    }

    /**
     * Search users by username (read-only)
     */
    public function searchUsers(Request $request)
    {
        try {
            $query = $request->get('q', '');
            
            $users = Member::select('id', 'username', 'email', 'created_at')
                ->where('username', 'LIKE', '%' . $query . '%')
                ->limit(20)
                ->get();

            // Add default profile icon for each user
            $users->each(function ($user) {
                $user->profile_icon = $this->getDefaultProfileIcon();
                $user->display_name = $user->username ?? 'Anonymous';
            });

            return response()->json([
                'success' => true,
                'users' => $users
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in searchUsers: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error searching users: ' . $e->getMessage()
            ], 500);
        }
    }
}
?>