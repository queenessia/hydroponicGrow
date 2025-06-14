<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\SharingController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Auth;

// Route untuk halaman navbar
Route::get('/navbar', function () {
    return view('navbar');
});

// Route untuk halaman utama
Route::get('/page1', function () {
    return view('homepage');
});

// Route untuk halaman article
Route::get('/page2', function () {
    return view('article');
});

// Route untuk halaman video
Route::get('/page3', function () {
    return view('video');
});

// Halaman utama untuk menggabungkan ketiganya
Route::get('/', function () {
    return view('main');
});

// Sharing routes - accessible by both admin and members and guests
Route::get('/sharing', [SharingController::class, 'index'])->name('sharing.index');

// API routes that work for all user types (admin, member, guest)
Route::get('/api/posts', [SharingController::class, 'getPosts'])->name('api.posts');
Route::get('/api/users', [SharingController::class, 'getUsers'])->name('api.users');
Route::get('/api/current-user', [SharingController::class, 'getCurrentUser'])->name('api.current-user');
Route::get('/api/search-users', [SharingController::class, 'searchUsers'])->name('api.search-users');

// Public API routes
Route::get('/api/videos', [VideoController::class, 'getPublicVideos']);
// ADD THIS: Public route for articles - accessible by all users
Route::get('/api/articles', [ArtikelController::class, 'getPublicArticles'])->name('api.articles');

// Route Dashboard dengan logic pemeriksaan login
Route::get('/dashboard', function () {
    // Cek apakah user sudah login sebagai admin
    if (Auth::guard('web')->check()) {
        return redirect()->route('admin.dashboard');
    }
    
    // Cek apakah user sudah login sebagai member
    if (Auth::guard('member')->check()) {
        return redirect()->route('user.dashboard');
    }
    
    // Jika belum login, redirect ke sign in
    return redirect()->route('sign_in');
})->name('dashboard');

// Plant routes
Route::get('/tanaman', [PlantController::class, 'index'])->name('plants.index');
Route::get('/tanaman/{id}', [PlantController::class, 'show'])->name('plants.show');

Route::get('/dashboard_admin', function () {
    return view('dashboard_admin');
})->name('dashboard_admin');

Route::get('/dashboard_user', function () {
    return view('dashboard_user');
})->name('dashboard_user');

// Auth Routes
Route::get('/sign_up', [AuthController::class, 'showSignUpForm'])->name('sign_up');
Route::get('/sign_in', [AuthController::class, 'showSignInForm'])->name('sign_in');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes (menggunakan guard 'web')
Route::middleware(['auth:web'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // Admin can view posts but not interact
    Route::get('/posts', [PostController::class, 'index'])->name('admin.posts.index');
    
    // Admin artikel routes - ONLY for admin management
    Route::resource('artikel', ArtikelController::class);
    Route::get('artikel-data', [ArtikelController::class, 'index'])->name('artikel.data');
    Route::get('artikel/{id}/edit', [ArtikelController::class, 'edit']);
    Route::put('artikel/{id}', [ArtikelController::class, 'update']);
    
    // Admin video routes
    Route::get('video-data', [VideoController::class, 'index'])->name('admin.video.data');
    Route::post('video', [VideoController::class, 'store'])->name('admin.video.store');
    Route::get('video/{video}/edit', [VideoController::class, 'edit'])->name('admin.video.edit');
    Route::put('video/{video}', [VideoController::class, 'update'])->name('admin.video.update');
    Route::delete('video/{video}', [VideoController::class, 'destroy'])->name('admin.video.destroy');
});

// User Routes (menggunakan guard 'member')
Route::middleware(['auth:member'])->prefix('user')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('user.profile');
    Route::post('/upload-photo', [UserDashboardController::class, 'uploadPhoto'])->name('user.upload.photo');
    Route::post('/update-profile', [UserDashboardController::class, 'updateProfile'])->name('user.update.profile');
});

// Plant Routes
Route::prefix('plants')->name('plants.')->group(function () {
    Route::get('/', [PlantController::class, 'index'])->name('index');
    Route::get('/create', [PlantController::class, 'create'])->name('create');
    Route::post('/', [PlantController::class, 'store'])->name('store');
    Route::get('/{id}', [PlantController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [PlantController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PlantController::class, 'update'])->name('update');
    Route::delete('/{id}', [PlantController::class, 'destroy'])->name('destroy');
});

// API Routes untuk AJAX frontend
Route::prefix('api/plants')->name('api.plants.')->group(function () {
    Route::get('/', [PlantController::class, 'index'])->name('index');
    Route::post('/', [PlantController::class, 'store'])->name('store');
    Route::get('/{id}', [PlantController::class, 'show'])->name('show');
    Route::post('/{id}', [PlantController::class, 'update'])->name('update');
    Route::delete('/{id}', [PlantController::class, 'destroy'])->name('destroy');
    
});

// Member-only routes untuk posting dan interaksi
Route::middleware('auth:member')->group(function () {
    // Post management - only members can create and interact with posts
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::post('/posts/{post}/like', [PostController::class, 'toggleLike'])->name('posts.like');
    Route::post('/posts/{post}/bookmark', [PostController::class, 'toggleBookmark'])->name('posts.bookmark');
    Route::post('/posts/{post}/reply', [PostController::class, 'reply'])->name('posts.reply');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    
    
    // Member can update their own username
    Route::put('/api/update-username', [SharingController::class, 'updateUsername'])->name('api.update-username');
    
    // Dashboard routes for members
    Route::get('/dashboard/my-posts', [UserDashboardController::class, 'myPosts'])->name('dashboard.my-posts');
    Route::get('/dashboard/liked-posts', [UserDashboardController::class, 'likedPosts'])->name('dashboard.liked-posts');
    Route::get('/dashboard/bookmarked-posts', [UserDashboardController::class, 'bookmarkedPosts'])->name('dashboard.bookmarked-posts');
    Route::get('/dashboard/stats', [UserDashboardController::class, 'dashboardStats'])->name('dashboard.stats');
});

// Create a middleware that allows both admin and member access
Route::middleware(['auth:web,member'])->group(function () {
    // Routes that both admin and members can access
    // But with different permissions/functionalities
});

?>