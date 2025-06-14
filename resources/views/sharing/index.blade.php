<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sharing Experience - HydroponicGrow</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sharing.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Amita:wght@400;700&display=swap" rel="stylesheet">
    
    <style>
        /* Adjustment untuk navbar di halaman sharing */
        body {
            padding-top: 70px; /* Space untuk navbar yang fixed */
        }
        
        .container {
            margin-top: 20px; /* Extra space dari navbar */
        }
        
        /* Responsive adjustment */
        @media (max-width: 768px) {
            body {
                padding-top: 60px;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>
    
    <!-- Navbar -->
    <nav class="transparent-nav">
        <div class="logo-container">
            <img src="/image/logo.png" alt="Logo" class="logo" onerror="this.style.display='none'">
            <div class="brand-name">Hydroponic Grow</div>
        </div>
        
        <div class="nav-links" id="navLinks">
            <!-- Navigation dengan smooth scroll - untuk kembali ke homepage -->
            <a href="{{ url('/') }}#home" id="homeLink">Home</a>
            <a href="{{ url('/') }}#article" id="articleLink">Article</a>
            <a href="{{ url('/') }}#video" id="videoLink">Video</a>
            
            <!-- Link ke sharing - highlight karena sedang di halaman ini -->
            @auth('web')
                <a href="{{ route('sharing.index') }}" class="active">Sharing</a>
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            @elseauth('member')  
                <a href="{{ route('sharing.index') }}" class="active">Sharing</a>
                <a href="{{ route('user.dashboard') }}">Dashboard</a>
            @else
                <a href="{{ route('sign_in') }}">Sharing</a>
                <a href="{{ route('dashboard') }}">Dashboard</a>
            @endauth
        </div>
        
        <div class="mobile-menu" id="mobileMenu">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container">
        <!-- Left Sidebar -->
        <div class="left-side">
            <!-- User Profile -->
            <div class="profile-section">
                @auth('member')
                    <img src="{{ auth('member')->user()->profile_image ? asset('storage/profile_images/' . auth('member')->user()->profile_image) : asset('image/user.png') }}" 
                         alt="Foto Profil" class="profile-pic">
                    <span class="username">{{ auth('member')->user()->username }}</span>
                @else
                    <img src="/image/user.png" alt="Foto Profil" class="profile-pic">
                    <span class="username">Admin</span>
                @endauth
            </div>

            @auth('member')
                <!-- Navigation Items -->
                <div class="left-side-item" id="manage-posts-btn" onclick="window.location.href='{{ url('/dashboard') }}'">
                    <i class="fas fa-cogs"></i>
                    <span>Manage Post</span>
                </div>

                <!-- Create Post Button -->
                <div class="post-button" id="create-post-btn">
                    <i class="fas fa-pencil-alt"></i>
                    <span>Create Post</span>
                </div>
            @else
                <div class="left-side-item">
                    <i class="fas fa-info-circle"></i>
                    <span>Login as User to Posting</span>
                </div>
            @endauth
        </div>

        <!-- Main Content -->
        <div class="right-side">
            <!-- Header -->
            <div class="share-experience">
                <span id="page-title">Share Your Experience</span>
            </div>

            <!-- Posts Container -->
            <div id="posts-container">
                <div class="posts-loading">
                    <i class="fas fa-spinner fa-spin"></i>
                    <br>Loading posts...
                </div>
            </div>

            <!-- Load More Button -->
            <div id="load-more-container" style="text-align: center; margin-top: 20px; display: none;">
                <button id="load-more-btn" class="btn" style="background: #667eea; color: white;">
                    Load More Posts
                </button>
            </div>
        </div>
    </div>

    <!-- Post Creation Modal -->
    <div id="post-modal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Create New Post</h3>
                <button type="button" class="close-modal">&times;</button>
            </div>
            <form id="post-form" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="form-label">Share your experience</label>
                    <textarea name="content" id="post-content" class="form-textarea" 
                              placeholder="What's on your mind about hydroponics?" 
                              maxlength="1000" required></textarea>
                    <small style="color: #666; font-size: 12px;">
                        <span id="char-count">0</span>/1000 characters
                    </small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Add Images (Optional)</label>
                    <div class="file-input-container">
                        <input type="file" name="images[]" id="post-images" class="file-input" 
                               multiple accept="image/*">
                        <label for="post-images" class="file-input-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <div>Click to upload images</div>
                            <small>PNG, JPG, GIF up to 2MB each</small>
                        </label>
                    </div>
                    <div id="image-preview" class="image-preview"></div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-cancel" id="cancel-post">
                        <i class="fas fa-times"></i>
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-submit" id="submit-post">
                        <i class="fas fa-paper-plane"></i>
                        Post
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reply Modal -->
    <div id="reply-modal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Reply to Post</h3>
                <button type="button" class="close-modal">&times;</button>
            </div>
            <div id="original-post-preview"></div>
            <form id="reply-form" enctype="multipart/form-data">
                <input type="hidden" name="parent_id" id="reply-parent-id">
                <div class="form-group">
                    <label class="form-label">Your reply</label>
                    <textarea name="content" id="reply-content" class="form-textarea" 
                              placeholder="Write your reply..." 
                              maxlength="1000" required></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Add Images (Optional)</label>
                    <div class="file-input-container">
                        <input type="file" name="images[]" id="reply-images" class="file-input" 
                               multiple accept="image/*">
                        <label for="reply-images" class="file-input-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <div>Click to upload images</div>
                            <small>PNG, JPG, GIF up to 2MB each</small>
                        </label>
                    </div>
                    <div id="reply-image-preview" class="image-preview"></div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-cancel">
                        <i class="fas fa-times"></i>
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-reply"></i>
                        Reply
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Hidden data for JavaScript -->
    <script>
        window.APP_CONFIG = {
            isAuthenticated: {{ auth('member')->check() ? 'true' : 'false' }},
            userType: '{{ auth('member')->check() ? 'member' : (auth('web')->check() ? 'admin' : 'guest') }}',
            @auth('member')
            currentUser: {
                id: {{ auth('member')->id() }},
                username: '{{ auth('member')->user()->username }}',
                profileImage: '{{ auth('member')->user()->profile_image ? asset('storage/profile_images/' . auth('member')->user()->profile_image) : asset('image/user.png') }}'
            },
            @else
            currentUser: null,
            @endauth
            csrfToken: '{{ csrf_token() }}'
        };
        
        // Debug log
        console.log('APP_CONFIG loaded:', window.APP_CONFIG);
    </script>

    <!-- JavaScript -->
    <script>
        // Mobile menu functionality (dari navbar)
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenu = document.getElementById('mobileMenu');
            const navLinks = document.getElementById('navLinks');
            const mobileOverlay = document.getElementById('mobileOverlay');
            
            // Check if elements exist
            if (mobileMenu && navLinks) {
                console.log('Mobile menu elements found');
                
                // Toggle mobile menu when hamburger is clicked
                mobileMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                    console.log('Mobile menu clicked');
                    
                    mobileMenu.classList.toggle('active');
                    navLinks.classList.toggle('active');
                    mobileOverlay.classList.toggle('active');
                    
                    // Prevent body scroll when menu is open
                    if (navLinks.classList.contains('active')) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = '';
                    }
                });
                
                // Close menu when clicking on a navigation link
                const navLinksItems = navLinks.querySelectorAll('a');
                navLinksItems.forEach(link => {
                    link.addEventListener('click', function() {
                        console.log('Nav link clicked');
                        closeMenu();
                    });
                });
                
                // Close menu when clicking on overlay
                mobileOverlay.addEventListener('click', function() {
                    console.log('Overlay clicked');
                    closeMenu();
                });
                
                // Close menu when clicking outside (fallback)
                document.addEventListener('click', function(event) {
                    if (!mobileMenu.contains(event.target) && 
                        !navLinks.contains(event.target) && 
                        navLinks.classList.contains('active')) {
                        closeMenu();
                    }
                });
                
                // Close menu when window is resized to desktop size
                window.addEventListener('resize', function() {
                    if (window.innerWidth > 768) {
                        closeMenu();
                    }
                });
                
                // Function to close menu
                function closeMenu() {
                    mobileMenu.classList.remove('active');
                    navLinks.classList.remove('active');
                    mobileOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
                
                // Handle escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && navLinks.classList.contains('active')) {
                        closeMenu();
                    }
                });
                
            } else {
                console.log('Mobile menu elements not found');
            }
        });
    </script>
    <script src="{{ asset('js/sharing.js') }}"></script>
</body>
</html>