<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard User</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/dashboard_user.css') }}">
  <!-- Tambahkan ini di dalam tag <head> -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <style>
    /* Adjustment untuk navbar di halaman dashboard user */
    body {
      padding-top: 70px; /* Space untuk navbar yang fixed */
      margin: 0;
      border-top: 3px solid #396929; /* Garis hijau di atas layar */
    }
    
    /* Adjust drawer toggle position */
    .drawer-toggle {
      top: 80px; /* Move below navbar */
      z-index: 999; /* Below navbar but above content */
    }
    
    /* Adjust drawer position */
    .drawer {
      top: 70px; /* Start below navbar */
      height: calc(100vh - 70px); /* Full height minus navbar */
    }
    
    /* Adjust content area */
    .content {
      margin-top: 10px; /* Small margin from navbar */
    }
    
    /* Responsive adjustment */
    @media (max-width: 768px) {
      body {
        padding-top: 60px;
        border-top: 2px solid #396929; /* Garis hijau lebih tipis di mobile */
      }
      
      .drawer-toggle {
        top: 70px;
      }
      
      .drawer {
        top: 60px;
        height: calc(100vh - 60px);
      }
    }

    /* Additional styles for edit profile functionality */
    .edit-profile-btn {
      background: #3A7F0D;
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 14px;
      font-weight: 500;
      margin-top: 20px;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all 0.3s ease;
    }
    
    .edit-profile-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
    
    .edit-form-container {
      background: white;
      border-radius: 12px;
      padding: 30px;
      margin-top: 20px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      display: none;
    }
    
    .edit-form-container.active {
      display: block;
      animation: slideDown 0.3s ease;
    }
    
    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    .form-label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      color: #333;
      font-size: 14px;
    }
    
    .form-input {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid #e1e5e9;
      border-radius: 8px;
      font-size: 14px;
      transition: border-color 0.3s ease;
      box-sizing: border-box;
    }
    
    .form-input:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .form-input:invalid {
      border-color: #e74c3c;
    }
    
    .form-buttons {
      display: flex;
      gap: 12px;
      justify-content: flex-end;
      margin-top: 30px;
    }
    
    .btn-save {
      background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 14px;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    
    .btn-save:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(39, 174, 96, 0.4);
    }
    
    .btn-cancel {
      background: #95a5a6;
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 14px;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    
    .btn-cancel:hover {
      background: #7f8c8d;
      transform: translateY(-2px);
    }
    
    .password-section {
      border-top: 1px solid #e1e5e9;
      padding-top: 20px;
      margin-top: 20px;
    }
    
    .section-title {
      font-size: 16px;
      font-weight: 600;
      color: #333;
      margin-bottom: 15px;
    }
    
    .alert {
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 20px;
      display: none;
      font-size: 14px;
    }
    
    .alert.success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    
    .alert.error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
    
    .loading-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }
    
    .loading-spinner {
      width: 40px;
      height: 40px;
      border: 4px solid #f3f3f3;
      border-top: 4px solid #667eea;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
</head>
<body class="page-dashboard" onload="initializePage()"> <!-- GARIS HIJAU TETAP di "Dashboard" -->
  <!-- Loading overlay -->
  <div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
  </div>

  <!-- Mobile Overlay -->
  <div class="mobile-overlay" id="mobileOverlay"></div>
  
  <!-- Navbar -->
  <nav class="transparent-nav">
    <div class="logo-container">
      <img src="/image/logo.png" alt="Logo" class="logo" onerror="this.style.display='none'">
      <div class="brand-name">Hydroponic Grow</div>
    </div>
    
    <div class="nav-links" id="navLinks">
      <!-- Dashboard akan memiliki garis hijau karena body class="page-dashboard" -->
      <a href="{{ url('/') }}" id="homeLink">Home</a>
      <a href="{{ url('/article') }}" id="articleLink">Article</a>
      <a href="{{ url('/video') }}" id="videoLink">Video</a>
      
      @auth('web')
        <a href="{{ route('sharing.index') }}">Sharing</a>
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
      @elseauth('member')  
        <a href="{{ route('sharing.index') }}">Sharing</a>
        <a href="{{ route('user.dashboard') }}">Dashboard</a> <!-- GARIS HIJAU TETAP DISINI -->
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

  <button class="drawer-toggle" onclick="toggleDrawer()"></button>

  <div id="drawer" class="drawer">
    <!-- New Profile Menu -->
    <div class="menu" onclick="toggleSubMenu('profilSubmenu')">
      <i class="fas fa-user-circle"></i> Profile
    </div>
    <div id="profilSubmenu" class="submenu">
      <div onclick="showKelolaAkun()"><i class="fas fa-cog"></i>Account Information</div>
      <div onclick="logout()"><i class="fas fa-sign-out-alt"></i>Logout</div>
    </div>
    
    <div class="menu active" onclick="showDashboard()">
      <i class="fas fa-chart-line"></i> Dashboard
    </div>
    
    <div class="menu" onclick="toggleSubMenu('adminSubmenu')">
      <i class="fas fa-user-shield"></i> Manage Post
    </div>
   <div id="adminSubmenu" class="submenu">
  <div onclick="showPost()"><i class="fas fa-comment"></i>Post</div>
  <div onclick="showBookmarkTable()"><i class="fas fa-bookmark"></i>Bookmark</div>
  <div onclick="showLikeTable()"><i class="fas fa-heart"></i>Like</div>
</div>
  </div>

  <div class="content" id="mainContent">
    <!-- Content will be loaded dynamically -->
  </div>

  <!-- Hidden file input -->
  <input type="file" id="photoInput" accept="image/*">

  <script>
    // Mobile menu functionality (dari navbar)
    document.addEventListener('DOMContentLoaded', function() {
      const mobileMenu = document.getElementById('mobileMenu');
      const navLinks = document.getElementById('navLinks');
      const mobileOverlay = document.getElementById('mobileOverlay');
      
      if (mobileMenu && navLinks) {
        mobileMenu.addEventListener('click', function(e) {
          e.stopPropagation();
          mobileMenu.classList.toggle('active');
          navLinks.classList.toggle('active');
          mobileOverlay.classList.toggle('active');
          
          if (navLinks.classList.contains('active')) {
            document.body.style.overflow = 'hidden';
          } else {
            document.body.style.overflow = '';
          }
        });
        
        const navLinksItems = navLinks.querySelectorAll('a');
        navLinksItems.forEach(link => {
          link.addEventListener('click', function() {
            closeMenu();
          });
        });
        
        mobileOverlay.addEventListener('click', function() {
          closeMenu();
        });
        
        document.addEventListener('click', function(event) {
          if (!mobileMenu.contains(event.target) && 
              !navLinks.contains(event.target) && 
              navLinks.classList.contains('active')) {
            closeMenu();
          }
        });
        
        window.addEventListener('resize', function() {
          if (window.innerWidth > 768) {
            closeMenu();
          }
        });
        
        function closeMenu() {
          mobileMenu.classList.remove('active');
          navLinks.classList.remove('active');
          mobileOverlay.classList.remove('active');
          document.body.style.overflow = '';
        }
        
        document.addEventListener('keydown', function(e) {
          if (e.key === 'Escape' && navLinks.classList.contains('active')) {
            closeMenu();
          }
        });
      }
    });

    // // Define dashboard content as a constant
    // const dashboardContent = `
    //   <h1 class="content-title">General Information</h1>
      
    //   <!-- Container untuk box 1-3 -->
    //   <div class="box-container">
    //     <!-- Box 1 -->
    //     <div class="box">
    //       <img src="/image/postUser.png" alt="Icon 1" class="box-icon">
    //       <h3>Post History</h3>
    //       <div class="big-zero">100</div>
    //     </div>
        
    //     <!-- Box 2 -->
    //     <div class="box">
    //       <img src="/image/videoAdmin.png" alt="Icon 2" class="box-icon">
    //       <h3>Bookmark History</h3>
    //       <div class="big-zero">100</div>
    //     </div>
        
    //     <!-- Box 3 -->
    //     <div class="box">
    //       <img src="/Image/tanamanAdmin.png" alt="Icon 3" class="box-icon">
    //       <h3>Like History</h3>
    //       <div class="big-zero">0</div>
    //     </div>
    //    </div>`;

    function initializePage() {
      // Buka drawer dan tampilkan dashboard saat halaman dimuat
      toggleDrawer();
      showDashboard();
    }

    function toggleDrawer() {
      const drawer = document.getElementById("drawer");
      const content = document.getElementById("mainContent");
      drawer.classList.toggle("open");
      content.classList.toggle("drawer-open");
    }

    function toggleSubMenu(id) {
      const submenu = document.getElementById(id);
      submenu.classList.toggle('active');
    }

    function updateActiveNav(menuIndex) {
      // Update active state in sidebar
      const menus = document.querySelectorAll('.menu');
      menus.forEach((menu, index) => {
        if (index === menuIndex) {
          menu.classList.add('active');
        } else {
          menu.classList.remove('active');
        }
      });
      
      // Update active state in top navigation
      const navLinks = document.querySelectorAll('.nav-links a');
      navLinks.forEach((link, index) => {
        if (index === menuIndex) {
          link.classList.add('active-nav');
        } else {
          link.classList.remove('active-nav');
        }
      });
    }

    function showDashboard() {
      updateActiveNav(1); // Dashboard is the second menu item (index 1)
      
      // Kosongkan submenu aktif jika ada
      const submenuItems = document.querySelectorAll('.submenu div');
      submenuItems.forEach(item => item.classList.remove('active-item'));
      
      // Update konten dengan dashboard
      document.getElementById("mainContent").innerHTML = dashboardContent;
    }

    function showHome() {
      updateActiveNav(0);
      document.getElementById("mainContent").innerHTML = `
        <h1 class="content-title">Home</h1>
        <p>This is the home page content.</p>`;
    }

    function showArticle() {
      updateActiveNav(1);
      document.getElementById("mainContent").innerHTML = `
        <h1 class="content-title">Articles</h1>
        <p>This is the articles page content.</p>`;
    }

    function showVideo() {
      updateActiveNav(2);
      document.getElementById("mainContent").innerHTML = `
        <h1 class="content-title">Videos</h1>
        <p>This is the videos page content.</p>`;
    }

    function showSharing() {
      updateActiveNav(3);
      document.getElementById("mainContent").innerHTML = `
        <h1 class="content-title">Sharing</h1>
        <p>This is the sharing page content.</p>`;
    }

    // Data user dari backend
    const userData = {
        fullName: "{{ $user->full_name }}",
        firstName: "{{ $user->first_name }}",
        lastName: "{{ $user->last_name }}",
        username: "{{ $user->username }}",
        email: "{{ $user->email }}",
        profileImage: "{{ $user->profile_image ? asset('storage/profile_images/' . $user->profile_image) : asset('image/user.png') }}"
    };

    function showKelolaAkun() {
        // Set menu Profil sebagai aktif
        updateActiveNav(0);
        
        // Set submenu Kelola Akun sebagai aktif
        const submenuItems = document.querySelectorAll('#profilSubmenu div');
        submenuItems.forEach(item => item.classList.remove('active-item'));
        submenuItems[0].classList.add('active-item');
        
        document.getElementById("mainContent").innerHTML = `
            <h1 class="content-title">Account Information</h1>
            
            <!-- Alert messages -->
            <div id="alertSuccess" class="alert success"></div>
            <div id="alertError" class="alert error"></div>
            
            <!-- Profil User -->
            <div class="user-profile">
                <img id="userProfileImage" src="${userData.profileImage}" alt="User Photo" class="user-image">
                <div class="user-name">${userData.fullName}</div>
            </div>

            <!-- Upload New Photo Button -->
            <button class="upload-photo-btn" onclick="triggerFileInput()">
                <i class="fas fa-camera"></i> Upload New Photo
            </button>
            
            <!-- Box informasi user -->
            <div class="user-info-container">
                <div class="info-label">Username</div>
                <div class="info-box">${userData.username}</div>
                
                <div class="info-label">Email</div>
                <div class="info-box">${userData.email}</div>
            </div>
            
            <!-- Edit Profile Button -->
            <button class="edit-profile-btn" onclick="toggleEditForm()">
                <i class="fas fa-edit"></i> Edit Profile
            </button>
            
            <!-- Edit Profile Form -->
            <div id="editFormContainer" class="edit-form-container">
                <h3 class="section-title">Edit Profile Information</h3>
                
                <form id="editProfileForm">
                    <div class="form-group">
                        <label class="form-label">First Name</label>
                        <input type="text" id="firstName" class="form-input" value="${userData.firstName}" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Last Name</label>
                        <input type="text" id="lastName" class="form-input" value="${userData.lastName}" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" id="username" class="form-input" value="${userData.username}" required pattern="[a-zA-Z0-9_]+">
                        <small style="color: #666; font-size: 12px;">Only letters, numbers, and underscores allowed</small>
                    </div>
                    
                    <div class="password-section">
                        <h4 class="section-title">Change Password (Optional)</h4>
                        
                        <div class="form-group">
                            <label class="form-label">Current Password</label>
                            <input type="password" id="currentPassword" class="form-input">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">New Password</label>
                            <input type="password" id="newPassword" class="form-input" minlength="8">
                            <small style="color: #666; font-size: 12px;">Minimum 8 characters</small>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" id="confirmPassword" class="form-input" minlength="8">
                        </div>
                    </div>
                    
                    <div class="form-buttons">
                        <button type="button" class="btn-cancel" onclick="cancelEdit()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        `;
        
        // Add event listener for form submission
        setTimeout(() => {
            const form = document.getElementById('editProfileForm');
            if (form) {
                form.addEventListener('submit', handleProfileUpdate);
            }
        }, 100);
    }

    // Function to toggle edit form visibility
    function toggleEditForm() {
        const container = document.getElementById('editFormContainer');
        container.classList.toggle('active');
    }

    // Function to cancel edit and hide form
    function cancelEdit() {
        const container = document.getElementById('editFormContainer');
        container.classList.remove('active');
        
        // Reset form values
        document.getElementById('firstName').value = userData.firstName;
        document.getElementById('lastName').value = userData.lastName;
        document.getElementById('username').value = userData.username;
        document.getElementById('currentPassword').value = '';
        document.getElementById('newPassword').value = '';
        document.getElementById('confirmPassword').value = '';
    }

    // Function to handle profile update
    function handleProfileUpdate(e) {
        e.preventDefault();
        
        const firstName = document.getElementById('firstName').value.trim();
        const lastName = document.getElementById('lastName').value.trim();
        const username = document.getElementById('username').value.trim();
        const currentPassword = document.getElementById('currentPassword').value;
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        
        // Validation
        if (!firstName || !lastName || !username) {
            showAlert('error', 'Please fill in all required fields');
            return;
        }
        
        if (!/^[a-zA-Z0-9_]+$/.test(username)) {
            showAlert('error', 'Username can only contain letters, numbers, and underscores');
            return;
        }
        
        if (newPassword && newPassword !== confirmPassword) {
            showAlert('error', 'New password and confirmation do not match');
            return;
        }
        
        if (newPassword && newPassword.length < 8) {
            showAlert('error', 'New password must be at least 8 characters long');
            return;
        }
        
        if (newPassword && !currentPassword) {
            showAlert('error', 'Current password is required to change password');
            return;
        }
        
        // Show loading
        showLoading();
        
        // Create FormData
        const formData = new FormData();
        formData.append('first_name', firstName);
        formData.append('last_name', lastName);
        formData.append('username', username);
        
        if (newPassword) {
            formData.append('current_password', currentPassword);
            formData.append('new_password', newPassword);
            formData.append('new_password_confirmation', confirmPassword);
        }
        
        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            formData.append('_token', csrfToken.getAttribute('content'));
        }
        
        // Send update request
        fetch('/user/update-profile', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            
            if (data.success) {
                // Update userData object
                userData.firstName = data.user.first_name;
                userData.lastName = data.user.last_name;
                userData.fullName = data.user.full_name;
                userData.username = data.user.username;
                
                // Hide edit form
                document.getElementById('editFormContainer').classList.remove('active');
                
                // Refresh the account management view
                showKelolaAkun();
                
                // Show success message
                setTimeout(() => {
                    showAlert('success', data.message);
                }, 100);
                
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showAlert('error', 'An error occurred while updating profile');
        });
    }

    // Function to trigger file input
    function triggerFileInput() {
        document.getElementById('photoInput').click();
    }

    // Function to show loading overlay
    function showLoading() {
        document.getElementById('loadingOverlay').style.display = 'flex';
    }

    // Function to hide loading overlay
    function hideLoading() {
        document.getElementById('loadingOverlay').style.display = 'none';
    }

    // Function to show alert messages
    function showAlert(type, message) {
        const alertElement = document.getElementById('alert' + (type === 'success' ? 'Success' : 'Error'));
        alertElement.textContent = message;
        alertElement.style.display = 'block';
        
        // Hide alert after 5 seconds
        setTimeout(() => {
            alertElement.style.display = 'none';
        }, 5000);
    }

    // Handle file input change
    document.getElementById('photoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            showAlert('error', 'File harus berupa gambar (JPEG, PNG, JPG, atau GIF)');
            return;
        }

        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            showAlert('error', 'Ukuran file maksimal 2MB');
            return;
        }

        // Show loading
        showLoading();

        // Create FormData
        const formData = new FormData();
        formData.append('profile_image', file);

        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            formData.append('_token', csrfToken.getAttribute('content'));
        }

        // Upload file
        fetch('{{ route("user.upload.photo") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            
            if (data.success) {
                // Update profile image immediately
                const profileImage = document.getElementById('userProfileImage');
                if (profileImage) {
                    profileImage.src = data.image_url + '?t=' + new Date().getTime(); // Add timestamp to prevent caching
                }
                
                // Update userData object
                userData.profileImage = data.image_url;
                
                showAlert('success', data.message);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showAlert('error', 'Terjadi kesalahan saat mengupload foto');
        });

        // Reset file input
        e.target.value = '';
    });

    // Load default content
    showKelolaAkun();

    // FUNGSI LOGOUT YANG DIPERBAIKI
    function logout() {
      if (confirm('Apakah Anda yakin ingin keluar?')) {
        // Show loading
        showLoading();
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        // Create form data
        const formData = new FormData();
        if (csrfToken) {
          formData.append('_token', csrfToken);
        }
        
        // Send logout request
        fetch('{{ route("logout") }}', {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        })
        .then(response => {
          hideLoading();
          
          if (response.ok) {
            // Redirect to sign in page
            window.location.href = '{{ route("sign_in") }}';
          } else {
            // Fallback: redirect anyway
            window.location.href = '{{ route("sign_in") }}';
          }
        })
        .catch(error => {
          hideLoading();
          console.error('Logout error:', error);
          
          // Fallback: redirect to sign in page
          window.location.href = '{{ route("sign_in") }}';
        });
      }
    }

    // Stub functions for missing features
    function showPost() {
      alert('Post management feature will be implemented');
    }

    function showBookmarkTable() {
      alert('Bookmark management feature will be implemented');
    }

    function showLikeTable() {
      alert('Like management feature will be implemented');
    }
        
    
  </script>
  <script src="{{ asset('js/dashboard-real-data.js') }}"></script>
</body>
</html>