<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\Post;

class UserDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:member'); // Guard untuk member
    }

    public function index()
    {
        $user = Auth::guard('member')->user();
        return view('dashboard_user', compact('user'));
    }

    public function profile()
    {
        $user = Auth::guard('member')->user();
        return view('user_profile', compact('user'));
    }

    public function uploadPhoto(Request $request)
    {
        try {
            // Validasi file
            $validator = Validator::make($request->all(), [
                'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'File tidak valid. Pastikan file adalah gambar (jpeg, png, jpg, gif) dan ukuran maksimal 2MB.'
                ], 400);
            }

            $user = Auth::guard('member')->user();
                     
            // Hapus foto lama jika ada
            if ($user->profile_image && $user->profile_image !== 'user.png') {
                Storage::disk('public')->delete('profile_images/' . $user->profile_image);
            }

            // Upload foto baru
            $file = $request->file('profile_image');
            $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
                     
            // Simpan ke storage/app/public/profile_images
            $file->storeAs('profile_images', $filename, 'public');

            // Update database
            $user->profile_image = $filename;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diupload!',
                'image_url' => asset('storage/profile_images/' . $filename)
            ]);

        } catch (\Exception $e) {
            Log::error('Upload photo error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupload foto: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::guard('member')->user();
            
            // Validasi input
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'username' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[a-zA-Z0-9_]+$/',
                    Rule::unique('members', 'username')->ignore($user->id)
                ],
                'current_password' => 'nullable|string',
                'new_password' => 'nullable|string|min:8|confirmed',
                'new_password_confirmation' => 'nullable|string|min:8'
            ], [
                'first_name.required' => 'Nama depan harus diisi',
                'last_name.required' => 'Nama belakang harus diisi',
                'username.required' => 'Username harus diisi',
                'username.unique' => 'Username sudah digunakan',
                'username.regex' => 'Username hanya boleh mengandung huruf, angka, dan underscore',
                'new_password.min' => 'Password baru minimal 8 karakter',
                'new_password.confirmed' => 'Konfirmasi password tidak cocok'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 400);
            }

            // Jika ingin mengubah password, verifikasi password lama
            if ($request->filled('new_password')) {
                if (!$request->filled('current_password')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Password lama harus diisi untuk mengubah password'
                    ], 400);
                }

                if (!Hash::check($request->current_password, $user->password)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Password lama tidak benar'
                    ], 400);
                }

                $user->password = Hash::make($request->new_password);
            }

            // Update data user
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->username = $request->username;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui!',
                'user' => [
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'full_name' => $user->full_name,
                    'username' => $user->username,
                    'email' => $user->email
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Update profile error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage()
            ], 500);
        }
    }

    public function myPosts(Request $request)
    {
        try {
            $memberId = auth('member')->id();
            
            // Check if Post model exists and has proper relationships
            if (!class_exists('App\Models\Post')) {
                return response()->json([
                    'success' => false,
                    'status' => 'error',
                    'message' => 'Post model not found'
                ], 500);
            }
            
            $query = Post::where('member_id', $memberId);
            
            // Check if relationships exist before using them
            $availableRelations = [];
            if (method_exists(Post::class, 'member')) {
                $availableRelations[] = 'member:id,first_name,last_name,username,profile_image';
            }
            
            if (method_exists(Post::class, 'likes')) {
                $availableRelations[] = 'likes';
                $query->with(['likes' => function($q) use ($memberId) {
                    $q->where('member_id', $memberId);
                }]);
            }
            
            if (method_exists(Post::class, 'bookmarks')) {
                $availableRelations[] = 'bookmarks';
                $query->with(['bookmarks' => function($q) use ($memberId) {
                    $q->where('member_id', $memberId);
                }]);
            }
            
            if (method_exists(Post::class, 'replies')) {
                $availableRelations[] = 'replies.member:id,first_name,last_name,username,profile_image';
            }
            
            if (method_exists(Post::class, 'parentPost')) {
                $availableRelations[] = 'parentPost.member:id,first_name,last_name,username,profile_image';
            }
            
            if (!empty($availableRelations)) {
                $query->with($availableRelations);
            }
            
            // Add counts if methods exist
            $countRelations = [];
            if (method_exists(Post::class, 'likes')) {
                $countRelations[] = 'likes';
            }
            if (method_exists(Post::class, 'bookmarks')) {
                $countRelations[] = 'bookmarks';
            }
            if (method_exists(Post::class, 'replies')) {
                $countRelations[] = 'replies';
            }
            
            if (!empty($countRelations)) {
                $query->withCount($countRelations);
            }
            
            $posts = $query->latest()->paginate(10);
            
            // Transform collection with error handling
            $posts->getCollection()->transform(function ($post) use ($memberId) {
                // Safe property access
                $post->is_liked = $post->relationLoaded('likes') ? $post->likes->isNotEmpty() : false;
                $post->is_bookmarked = $post->relationLoaded('bookmarks') ? $post->bookmarks->isNotEmpty() : false;
                $post->is_owner = $post->member_id === $memberId;
                
                // Handle image URL
                if (isset($post->image_path) && $post->image_path) {
                    $post->image_url = asset('storage/' . $post->image_path);
                    $post->images = [$post->image_path];
                } else {
                    $post->image_url = null;
                    $post->images = [];
                }
                
                // Ensure counts exist
                $post->likes_count = $post->likes_count ?? 0;
                $post->bookmarks_count = $post->bookmarks_count ?? 0;
                $post->replies_count = $post->replies_count ?? 0;
                
                return $post;
            });
            
            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Posts retrieved successfully',
                'data' => $posts
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in myPosts: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Failed to retrieve posts: ' . $e->getMessage(),
                'debug' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'memberId' => auth('member')->id()
                ]
            ], 500);
        }
    }

    public function deletePost(Request $request, $id)
    {
        try {
            $memberId = auth('member')->id();
            
            // Check if Post model exists
            if (!class_exists('App\Models\Post')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Post model not found'
                ], 500);
            }
            
            // Find the post
            $post = Post::find($id);
            
            if (!$post) {
                return response()->json([
                    'success' => false,
                    'message' => 'Post tidak ditemukan'
                ], 404);
            }
            
            // Check if user owns the post
            if ($post->member_id !== $memberId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk menghapus post ini'
                ], 403);
            }
            
            // Delete associated image if exists
            if (isset($post->image_path) && $post->image_path) {
                try {
                    Storage::disk('public')->delete($post->image_path);
                } catch (\Exception $e) {
                    Log::warning('Failed to delete image file: ' . $e->getMessage());
                }
            }
            
            // Delete likes if relationship exists
            if (method_exists(Post::class, 'likes')) {
                try {
                    $post->likes()->delete();
                } catch (\Exception $e) {
                    Log::warning('Failed to delete likes: ' . $e->getMessage());
                }
            }
            
            // Delete bookmarks if relationship exists
            if (method_exists(Post::class, 'bookmarks')) {
                try {
                    $post->bookmarks()->delete();
                } catch (\Exception $e) {
                    Log::warning('Failed to delete bookmarks: ' . $e->getMessage());
                }
            }
            
            // Delete replies if relationship exists
            if (method_exists(Post::class, 'replies')) {
                try {
                    // First delete likes and bookmarks of replies
                    $replies = $post->replies;
                    foreach ($replies as $reply) {
                        if (method_exists($reply, 'likes')) {
                            $reply->likes()->delete();
                        }
                        if (method_exists($reply, 'bookmarks')) {
                            $reply->bookmarks()->delete();
                        }
                        // Delete reply image if exists
                        if (isset($reply->image_path) && $reply->image_path) {
                            try {
                                Storage::disk('public')->delete($reply->image_path);
                            } catch (\Exception $e) {
                                Log::warning('Failed to delete reply image: ' . $e->getMessage());
                            }
                        }
                    }
                    // Delete all replies
                    $post->replies()->delete();
                } catch (\Exception $e) {
                    Log::warning('Failed to delete replies: ' . $e->getMessage());
                }
            }
            
            // Finally delete the post
            $post->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Post berhasil dihapus!'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in deletePost: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus post: ' . $e->getMessage()
            ], 500);
        }
    }

    public function likedPosts(Request $request)
    {
        try {
            $memberId = auth('member')->id();
            
            // Check if Post model and relationships exist
            if (!class_exists('App\Models\Post')) {
                return response()->json([
                    'success' => false,
                    'status' => 'error',
                    'message' => 'Post model not found'
                ], 500);
            }
            
            // Check if likes relationship exists
            if (!method_exists(Post::class, 'likes')) {
                return response()->json([
                    'success' => false,
                    'status' => 'error',
                    'message' => 'Likes relationship not found in Post model'
                ], 500);
            }
            
            $query = Post::whereHas('likes', function($q) use ($memberId) {
                $q->where('member_id', $memberId);
            });
            
            // Add relationships safely
            $availableRelations = [];
            if (method_exists(Post::class, 'member')) {
                $availableRelations[] = 'member:id,first_name,last_name,username,profile_image';
            }
            
            $availableRelations[] = 'likes';
            $query->with(['likes' => function($q) use ($memberId) {
                $q->where('member_id', $memberId);
            }]);
            
            if (method_exists(Post::class, 'bookmarks')) {
                $availableRelations[] = 'bookmarks';
                $query->with(['bookmarks' => function($q) use ($memberId) {
                    $q->where('member_id', $memberId);
                }]);
            }
            
            if (method_exists(Post::class, 'replies')) {
                $availableRelations[] = 'replies.member:id,first_name,last_name,username,profile_image';
            }
            
            if (method_exists(Post::class, 'parentPost')) {
                $availableRelations[] = 'parentPost.member:id,first_name,last_name,username,profile_image';
            }
            
            if (!empty($availableRelations)) {
                $query->with($availableRelations);
            }
            
            // Add counts
            $countRelations = ['likes'];
            if (method_exists(Post::class, 'bookmarks')) {
                $countRelations[] = 'bookmarks';
            }
            if (method_exists(Post::class, 'replies')) {
                $countRelations[] = 'replies';
            }
            
            $query->withCount($countRelations);
            
            $posts = $query->latest()->paginate(10);
            
            // Transform collection
            $posts->getCollection()->transform(function ($post) use ($memberId) {
                $post->is_liked = true; // Always true for liked posts
                $post->is_bookmarked = $post->relationLoaded('bookmarks') ? $post->bookmarks->isNotEmpty() : false;
                $post->is_owner = $post->member_id === $memberId;
                
                // Handle image
                if (isset($post->image_path) && $post->image_path) {
                    $post->image_url = asset('storage/' . $post->image_path);
                    $post->images = [$post->image_path];
                } else {
                    $post->image_url = null;
                    $post->images = [];
                }
                
                // Ensure counts
                $post->likes_count = $post->likes_count ?? 0;
                $post->bookmarks_count = $post->bookmarks_count ?? 0;
                $post->replies_count = $post->replies_count ?? 0;
                
                return $post;
            });
            
            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Liked posts retrieved successfully',
                'data' => $posts
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in likedPosts: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Failed to retrieve liked posts: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bookmarkedPosts(Request $request)
    {
        try {
            $memberId = auth('member')->id();
            
            // Check if Post model exists
            if (!class_exists('App\Models\Post')) {
                return response()->json([
                    'success' => false,
                    'status' => 'error',
                    'message' => 'Post model not found'
                ], 500);
            }
            
            // Check if bookmarks relationship exists
            if (!method_exists(Post::class, 'bookmarks')) {
                return response()->json([
                    'success' => false,
                    'status' => 'error',
                    'message' => 'Bookmarks relationship not found in Post model'
                ], 500);
            }
            
            $query = Post::whereHas('bookmarks', function($q) use ($memberId) {
                $q->where('member_id', $memberId);
            });
            
            // Add relationships safely
            $availableRelations = [];
            if (method_exists(Post::class, 'member')) {
                $availableRelations[] = 'member:id,first_name,last_name,username,profile_image';
            }
            
            if (method_exists(Post::class, 'likes')) {
                $availableRelations[] = 'likes';
                $query->with(['likes' => function($q) use ($memberId) {
                    $q->where('member_id', $memberId);
                }]);
            }
            
            $availableRelations[] = 'bookmarks';
            $query->with(['bookmarks' => function($q) use ($memberId) {
                $q->where('member_id', $memberId);
            }]);
            
            if (method_exists(Post::class, 'replies')) {
                $availableRelations[] = 'replies.member:id,first_name,last_name,username,profile_image';
            }
            
            if (method_exists(Post::class, 'parentPost')) {
                $availableRelations[] = 'parentPost.member:id,first_name,last_name,username,profile_image';
            }
            
            if (!empty($availableRelations)) {
                $query->with($availableRelations);
            }
            
            // Add counts
            $countRelations = ['bookmarks'];
            if (method_exists(Post::class, 'likes')) {
                $countRelations[] = 'likes';
            }
            if (method_exists(Post::class, 'replies')) {
                $countRelations[] = 'replies';
            }
            
            $query->withCount($countRelations);
            
            $posts = $query->latest()->paginate(10);
            
            // Transform collection
            $posts->getCollection()->transform(function ($post) use ($memberId) {
                $post->is_liked = $post->relationLoaded('likes') ? $post->likes->isNotEmpty() : false;
                $post->is_bookmarked = true; // Always true for bookmarked posts
                $post->is_owner = $post->member_id === $memberId;
                
                // Handle image
                if (isset($post->image_path) && $post->image_path) {
                    $post->image_url = asset('storage/' . $post->image_path);
                    $post->images = [$post->image_path];
                } else {
                    $post->image_url = null;
                    $post->images = [];
                }
                
                // Ensure counts
                $post->likes_count = $post->likes_count ?? 0;
                $post->bookmarks_count = $post->bookmarks_count ?? 0;
                $post->replies_count = $post->replies_count ?? 0;
                
                return $post;
            });
            
            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Bookmarked posts retrieved successfully',
                'data' => $posts
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in bookmarkedPosts: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Failed to retrieve bookmarked posts: ' . $e->getMessage()
            ], 500);
        }
    }

    public function dashboardStats()
    {
        try {
            $memberId = auth('member')->id();
            
            // Check if Post model exists
            if (!class_exists('App\Models\Post')) {
                return response()->json([
                    'success' => true,
                    'status' => 'success',
                    'message' => 'Stats retrieved successfully (default values)',
                    'data' => [
                        'total_posts' => 0,
                        'total_likes' => 0,
                        'total_bookmarks' => 0,
                        'posts_liked' => 0,
                        'posts_bookmarked' => 0,
                    ]
                ]);
            }
            
            // Initialize stats with safe defaults
            $stats = [
                'total_posts' => 0,
                'total_likes' => 0,
                'total_bookmarks' => 0,
                'posts_liked' => 0,
                'posts_bookmarked' => 0,
            ];
            
            // Get total posts by user
            $stats['total_posts'] = Post::where('member_id', $memberId)->count();
            
            // Get total likes received on user's posts (if likes relationship exists)
            if (method_exists(Post::class, 'likes')) {
                $userPosts = Post::where('member_id', $memberId)->withCount('likes')->get();
                $stats['total_likes'] = $userPosts->sum('likes_count');
                
                // Get posts user has liked
                $stats['posts_liked'] = Post::whereHas('likes', function($query) use ($memberId) {
                    $query->where('member_id', $memberId);
                })->count();
            }
            
            // Get total bookmarks received on user's posts (if bookmarks relationship exists)
            if (method_exists(Post::class, 'bookmarks')) {
                $userPosts = Post::where('member_id', $memberId)->withCount('bookmarks')->get();
                $stats['total_bookmarks'] = $userPosts->sum('bookmarks_count');
                
                // Get posts user has bookmarked
                $stats['posts_bookmarked'] = Post::whereHas('bookmarks', function($query) use ($memberId) {
                    $query->where('member_id', $memberId);
                })->count();
            }
            
            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Stats retrieved successfully',
                'data' => $stats
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in dashboardStats: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Return default stats on error
            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Stats retrieved successfully (default values due to error)',
                'data' => [
                    'total_posts' => 0,
                    'total_likes' => 0,
                    'total_bookmarks' => 0,
                    'posts_liked' => 0,
                    'posts_bookmarked' => 0,
                ],
                'debug_error' => $e->getMessage()
            ]);
        }
    }
}