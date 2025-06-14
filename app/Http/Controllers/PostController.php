<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function store(Request $request)
    {
        try {
            Log::info('PostController store called', [
                'request_data' => $request->all(),
                'files' => $request->hasFile('images') ? 'has files' : 'no files',
                'user_id' => Auth::guard('member')->id()
            ]);

            // Validation
            $request->validate([
                'content' => 'required|string|max:1000',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $memberId = Auth::guard('member')->id();
            if (!$memberId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }

            // Handle image uploads
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    try {
                        $path = $image->store('posts', 'public');
                        $imagePaths[] = $path;
                        Log::info('Image uploaded', ['path' => $path]);
                    } catch (\Exception $e) {
                        Log::error('Image upload failed', ['error' => $e->getMessage()]);
                    }
                }
            }

            // Create post
            $post = Post::create([
                'member_id' => $memberId,
                'content' => $request->content,
                'images' => $imagePaths,
                'parent_id' => $request->parent_id
            ]);

            // If this is a reply, increment the parent's replies count
            if ($request->parent_id) {
                Post::where('id', $request->parent_id)->increment('replies_count');
                Log::info('Parent post replies count incremented', ['parent_id' => $request->parent_id]);
            }

            Log::info('Post created', ['post_id' => $post->id, 'is_reply' => (bool)$request->parent_id]);

            // Load post with member relationship
            $post->load('member');

            return response()->json([
                'success' => true,
                'message' => $request->parent_id ? 'Reply posted successfully' : 'Post created successfully',
                'post' => $post
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Post creation failed', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Post $post)
    {
        try {
            $memberId = Auth::guard('member')->id();
            
            // Check if user is authenticated
            if (!$memberId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }

            // Check if user owns the post
            if ($post->member_id !== $memberId) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only delete your own posts'
                ], 403);
            }

            Log::info('Deleting post', [
                'post_id' => $post->id,
                'member_id' => $memberId,
                'has_replies' => $post->replies()->count() > 0,
                'has_images' => !empty($post->images)
            ]);

            // Delete associated images from storage
            if (!empty($post->images)) {
                foreach ($post->images as $imagePath) {
                    if (Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                        Log::info('Image deleted', ['path' => $imagePath]);
                    }
                }
            }

            // Delete associated likes
            $post->likes()->delete();
            Log::info('Post likes deleted', ['post_id' => $post->id]);

            // Delete associated bookmarks
            $post->bookmarks()->delete();
            Log::info('Post bookmarks deleted', ['post_id' => $post->id]);

            // Handle replies
            $repliesCount = $post->replies()->count();
            if ($repliesCount > 0) {
                // Delete all replies and their associated data
                $replies = $post->replies()->get();
                
                foreach ($replies as $reply) {
                    // Delete reply images
                    if (!empty($reply->images)) {
                        foreach ($reply->images as $imagePath) {
                            if (Storage::disk('public')->exists($imagePath)) {
                                Storage::disk('public')->delete($imagePath);
                            }
                        }
                    }
                    
                    // Delete reply likes and bookmarks
                    $reply->likes()->delete();
                    $reply->bookmarks()->delete();
                }
                
                // Delete all replies
                $post->replies()->delete();
                Log::info('Post replies deleted', ['post_id' => $post->id, 'replies_count' => $repliesCount]);
            }

            // If this is a reply, decrement the parent's replies count
            if ($post->parent_id) {
                Post::where('id', $post->parent_id)->decrement('replies_count');
                Log::info('Parent post replies count decremented', ['parent_id' => $post->parent_id]);
            }

            // Finally, delete the post
            $post->delete();

            Log::info('Post deleted successfully', ['post_id' => $post->id]);

            return response()->json([
                'success' => true,
                'message' => 'Post deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Post deletion failed', [
                'post_id' => $post->id ?? 'unknown',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleLike(Post $post)
    {
        try {
            $memberId = Auth::guard('member')->id();
            
            $like = $post->likes()->where('member_id', $memberId)->first();

            if ($like) {
                $like->delete();
                $post->decrement('likes_count');
                $isLiked = false;
            } else {
                $post->likes()->create(['member_id' => $memberId]);
                $post->increment('likes_count');
                $isLiked = true;
            }

            return response()->json([
                'success' => true,
                'is_liked' => $isLiked,
                'likes_count' => $post->fresh()->likes_count
            ]);

        } catch (\Exception $e) {
            Log::error('Toggle like failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error updating like'
            ], 500);
        }
    }

    public function toggleBookmark(Post $post)
    {
        try {
            $memberId = Auth::guard('member')->id();
            
            $bookmark = $post->bookmarks()->where('member_id', $memberId)->first();

            if ($bookmark) {
                $bookmark->delete();
                $isBookmarked = false;
            } else {
                $post->bookmarks()->create(['member_id' => $memberId]);
                $isBookmarked = true;
            }

            return response()->json([
                'success' => true,
                'is_bookmarked' => $isBookmarked
            ]);

        } catch (\Exception $e) {
            Log::error('Toggle bookmark failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error updating bookmark'
            ], 500);
        }
    }
}
?>