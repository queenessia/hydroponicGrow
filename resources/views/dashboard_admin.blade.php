<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Dashboard Admin</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/dashboard_admin.css') }}">
  
  <script>
    // Pass data user dari Laravel ke JavaScript
    window.userData = @json($user ?? []);
    window.csrfToken = '{{ csrf_token() }}';
  </script>
  
  <style>
   
  </style>
</head>
<body class="page-dashboard" onload="initializePage()"> <!-- GARIS HIJAU TETAP di "Dashboard" -->
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
        <a href="{{ route('admin.dashboard') }}">Dashboard</a> <!-- GARIS HIJAU TETAP DISINI -->
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
    <!-- Profile Menu -->
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
      <i class="fas fa-user-shield"></i> Admin
    </div>
    <div id="adminSubmenu" class="submenu">
      <div onclick="showArtikelTable()"><i class="fas fa-plus"></i>Article</div>
      <div onclick="showVideoTable()"><i class="fas fa-plus"></i>Video</div>
      <div onclick="showTanamanTable()"><i class="fas fa-plus"></i>Tutorial</div>
    </div>
  </div>

  <div class="content" id="mainContent">
    <!-- Content will be loaded dynamically -->
  </div>

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

    // ========== DASHBOARD CONTENT CONSTANTS ==========
    
    // Global variables to store counts
    let dashboardCounts = {
      artikel: 0,
      video: 0,
      tanaman: 0
    };

    // Function to get dashboard content with dynamic counts
    function getDashboardContent() {
      return `
        <h1 class="content-title">General Information</h1>
        
        <div class="box-container">
          <!-- Box 1 -->
          <div class="box">
            <img src="/image/artikelAdmin.png" alt="Icon 1" class="box-icon">
            <h3>Total Article</h3>
            <div class="big-zero" id="artikelCount">${dashboardCounts.artikel}</div>
          </div>
          
          <!-- Box 2 -->
          <div class="box">
            <img src="/image/videoAdmin.png" alt="Icon 2" class="box-icon">
            <h3>Total Video</h3>
            <div class="big-zero" id="videoCount">${dashboardCounts.video}</div>
          </div>
          
          <!-- Box 3 -->
          <div class="box">
            <img src="/image/tanamanAdmin.png" alt="Icon 3" class="box-icon">
            <h3>Total Tanaman Panduan Budidaya</h3>
            <div class="big-zero" id="tanamanCount">${dashboardCounts.tanaman}</div>
          </div>
        </div>
      `;
    }

    // Function to load dashboard counts
    async function loadDashboardCounts() {
      // Set loading state
      const artikelCountEl = document.getElementById('artikelCount');
      const videoCountEl = document.getElementById('videoCount');
      const tanamanCountEl = document.getElementById('tanamanCount');
      
      if (artikelCountEl) artikelCountEl.classList.add('loading');
      if (videoCountEl) videoCountEl.classList.add('loading');
      if (tanamanCountEl) tanamanCountEl.classList.add('loading');

      try {
        // Load artikel count
        const artikelResponse = await fetch('/admin/artikel-data', {
          method: 'GET',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
          }
        });
        
        if (artikelResponse.ok) {
          const artikelData = await artikelResponse.json();
          if (artikelData.success) {
            dashboardCounts.artikel = artikelData.data.length;
          }
        }

        // Load video count
        const videoResponse = await fetch('/admin/video-data', {
          method: 'GET',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
          }
        });
        
        if (videoResponse.ok) {
          const videoData = await videoResponse.json();
          if (videoData.success) {
            dashboardCounts.video = videoData.data.length;
          }
        }

        // Load tanaman count
        const tanamanResponse = await fetch('/api/plants', {
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        });
        
        if (tanamanResponse.ok) {
          const tanamanData = await tanamanResponse.json();
          if (tanamanData.success) {
            dashboardCounts.tanaman = tanamanData.data.length;
          }
        }

        // Update the display
        if (artikelCountEl) {
          artikelCountEl.textContent = dashboardCounts.artikel;
          artikelCountEl.classList.remove('loading');
        }
        if (videoCountEl) {
          videoCountEl.textContent = dashboardCounts.video;
          videoCountEl.classList.remove('loading');
        }
        if (tanamanCountEl) {
          tanamanCountEl.textContent = dashboardCounts.tanaman;
          tanamanCountEl.classList.remove('loading');
        }

      } catch (error) {
        console.error('Error loading dashboard counts:', error);
        
        // Remove loading state and show error
        if (artikelCountEl) {
          artikelCountEl.textContent = '!';
          artikelCountEl.classList.remove('loading');
        }
        if (videoCountEl) {
          videoCountEl.textContent = '!';
          videoCountEl.classList.remove('loading');
        }
        if (tanamanCountEl) {
          tanamanCountEl.textContent = '!';
          tanamanCountEl.classList.remove('loading');
        }
      }
    }

    const addArticleContent = `
      <h1 class="content-title">Tambah Artikel Baru</h1>
      
      <div class="form-container">
        <form id="articleForm">
          <div class="form-group">
            <label for="thumbnail">Thumbnail Artikel</label>
            <input type="file" id="thumbnail" class="form-control" accept="image/*" onchange="previewThumbnail(event)">
            <img id="thumbnailPreview" class="thumbnail-preview" alt="Thumbnail Preview" style="display: none; max-width: 200px; margin-top: 10px;">
          </div>
          
          <div class="form-group">
            <label for="publishedDate">Tanggal Publikasi</label>
            <input type="date" id="publishedDate" class="form-control" required>
          </div>
          
          <div class="form-group">
            <label for="source">Sumber Artikel</label>
            <input type="text" id="source" class="form-control" placeholder="Masukkan sumber artikel" required>
          </div>
          
          <div class="form-group">
            <label for="title">Judul Artikel</label>
            <input type="text" id="title" class="form-control" placeholder="Masukkan judul artikel" required>
          </div>
          
          <div class="form-group">
            <label for="description">Deskripsi Artikel</label>
            <textarea id="description" class="form-control" placeholder="Masukkan deskripsi artikel" required></textarea>
          </div>
          
          <div class="form-group">
            <label for="link">Link Artikel</label>
            <input type="url" id="link" class="form-control" placeholder="https://example.com" required>
          </div>
          
          <div class="form-group">
            <button type="button" class="btn-cancel" onclick="cancelForm('artikel')">Batal</button>
            <button type="submit" class="btn-submit">Simpan Artikel</button>
          </div>
        </form>
      </div>`;

    const addVideoContent = `
      <h1 class="content-title">Tambah Video Baru</h1>
      
      <div class="form-container">
        <form id="videoForm">
          <div class="form-group">
            <label for="videoTitle">Judul Video</label>
            <input type="text" id="videoTitle" class="form-control" placeholder="Masukkan Judul Video" required>
          </div>
          
          <div class="form-group">
            <label for="videoSource">Sumber Video</label>
            <input type="text" id="videoSource" class="form-control" placeholder="Masukkan sumber video" required>
          </div>

          <div class="form-group">
            <label for="publishedDate">Tanggal Publikasi</label>
            <input type="date" id="publishedDate" class="form-control" required>
          </div>
          
          <div class="form-group">
            <label for="videoLink">Link Video</label>
            <input type="url" id="videoLink" class="form-control" placeholder="https://example.com" required>
          </div>
          
          <div class="form-group">
            <button type="button" class="btn-cancel" onclick="cancelForm('video')">Batal</button>
            <button type="submit" class="btn-submit">Simpan Video</button>
          </div>
        </form>
      </div>`;

    const addTanamanContent = `
      <h1 class="content-title">Tambah Tanaman Baru</h1>
      
      <div class="form-container">
        <form id="tanamanForm">
          <div class="form-group">
            <label for="tanamanThumbnail">Thumbnail Tanaman</label>
            <input type="file" id="tanamanThumbnail" class="form-control" accept="image/*" onchange="previewTanamanThumbnail(event)">
            <img id="tanamanThumbnailPreview" class="thumbnail-preview" alt="Thumbnail Preview" style="display: none; max-width: 200px; margin-top: 10px;">
          </div>
          
          <div class="form-group">
            <label for="tanamanName">Nama Tanaman</label>
            <input type="text" id="tanamanName" class="form-control" placeholder="Masukkan nama tanaman" required>
          </div>
          
          <div class="form-group">
            <label for="tanamanSuhu">Suhu</label>
            <input type="text" id="tanamanSuhu" class="form-control" placeholder="Masukkan suhu" required>
          </div>
          
          <div class="form-group">
            <label for="tanamanDescription">Deskripsi Tanaman</label>
            <textarea id="tanamanDescription" class="form-control" placeholder="Masukkan deskripsi tanaman" required></textarea>
          </div>
          
          <div class="form-group">
            <button type="button" class="btn-cancel" onclick="cancelForm('tanaman')">Batal</button>
            <button type="submit" class="btn-submit">Simpan Tanaman</button>
          </div>
        </form>
      </div>`;

    // ========== CORE FUNCTIONS ==========

    function initializePage() {
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
      const menus = document.querySelectorAll('.menu');
      menus.forEach((menu, index) => {
        if (index === menuIndex) {
          menu.classList.add('active');
        } else {
          menu.classList.remove('active');
        }
      });
    }

    // ========== PREVIEW FUNCTIONS ==========

    function previewThumbnail(event) {
      const input = event.target;
      const preview = document.getElementById('thumbnailPreview');
      
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.src = e.target.result;
          preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
      }
    }

    function previewTanamanThumbnail(event) {
      const input = event.target;
      const preview = document.getElementById('tanamanThumbnailPreview');
      
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.src = e.target.result;
          preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
      }
    }
    
    function cancelForm(type) {
      if (confirm('Apakah Anda yakin ingin membatalkan? Perubahan yang belum disimpan akan hilang.')) {
        switch(type) {
          case 'artikel':
            showArtikelTable();
            break;
          case 'video':
            showVideoTable();
            break;
          case 'tanaman':
            showTanamanTable();
            break;
          default:
            showDashboard();
        }
      }
    }

    // ========== NAVIGATION FUNCTIONS ==========

    async function showDashboard() {
      updateActiveNav(1);
      const submenuItems = document.querySelectorAll('.submenu div');
      submenuItems.forEach(item => item.classList.remove('active-item'));
      
      // Display dashboard with current counts first
      document.getElementById("mainContent").innerHTML = getDashboardContent();
      
      // Then load fresh counts from server
      await loadDashboardCounts();
    }

    function showKelolaAkun() {
      const submenuItems = document.querySelectorAll('#profilSubmenu div');
      submenuItems.forEach(item => item.classList.remove('active-item'));
      submenuItems[0].classList.add('active-item');
      
      if (typeof window.userData === 'undefined' || !window.userData) {
        document.getElementById("mainContent").innerHTML = `
          <h1 class="content-title">Account Information</h1>
          <div class="error">Data user tidak tersedia. Silakan refresh halaman.</div>
        `;
        return;
      }
      
      const user = window.userData;
      
      document.getElementById("mainContent").innerHTML = `
        <h1 class="content-title">Kelola Akun</h1>
        
        <div class="user-info-container">
          <div class="info-label">Username</div>
          <div class="info-box">${user.username || 'N/A'}</div>
          
          <div class="info-label">Email</div>
          <div class="info-box">${user.email || 'N/A'}</div>
          
          <div class="info-label">Nama Depan</div>
          <div class="info-box">${user.first_name || 'N/A'}</div>
          
          <div class="info-label">Nama Belakang</div>
          <div class="info-box">${user.last_name || 'N/A'}</div>
          
          <div class="info-label">Role</div>
          <div class="info-box">Admin</div>
        </div>
      `;
    }

    function logout() {
      if (confirm('Apakah Anda yakin ingin keluar?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("logout") }}';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = window.csrfToken;
        
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
      }
    }

    // ========== ARTIKEL MANAGEMENT ==========

    function loadArtikelData() {
      fetch('/admin/artikel-data', {
          method: 'GET',
          headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
          }
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              displayArtikelTable(data.data);
              // Update dashboard count
              dashboardCounts.artikel = data.data.length;
          }
      })
      .catch(error => {
          console.error('Error:', error);
          alert('Error loading data artikel');
      });
    }

    function displayArtikelTable(artikels) {
      let tableHTML = `
          <h1 class="content-title">Kelola Artikel</h1>
          <button id="btn-add1" class="btn-add">+ Tambah Artikel</button>
          <table>
              <thead>
                  <tr>
                      <th>No</th>
                      <th>Thumbnail</th>
                      <th>Published Date</th>
                      <th>Source</th>
                      <th>Title</th>
                      <th>Description</th>
                      <th>Link</th>
                      <th>Aksi</th>
                  </tr>
              </thead>
              <tbody>`;
      
      if (artikels.length === 0) {
          tableHTML += `<tr><td colspan="8" style="text-align:center;">Tidak ada data artikel.</td></tr>`;
      } else {
          artikels.forEach((artikel, index) => {
              const thumbnailUrl = artikel.thumbnail ? `/storage/${artikel.thumbnail}` : '/image/default-thumbnail.png';
              tableHTML += `
                  <tr>
                      <td>${index + 1}</td>
                      <td><img src="${thumbnailUrl}" alt="Thumbnail" style="width: 50px; height: 50px; object-fit: cover;"></td>
                      <td>${artikel.published_date}</td>
                      <td>${artikel.source}</td>
                      <td>${artikel.title}</td>
                      <td>${artikel.description.substring(0, 50)}...</td>
                      <td><a href="${artikel.link}" target="_blank">Link</a></td>
                      
                      <td>
                          <button onclick="editArtikel(${artikel.id})" class="btn-edit">Edit</button>
                          <button onclick="deleteArtikel(${artikel.id})" class="btn-delete">Hapus</button>
                      </td>
                  </tr>`;
          });
      }
      
      tableHTML += `</tbody></table>`;
      document.getElementById("mainContent").innerHTML = tableHTML;
      document.getElementById("btn-add1").addEventListener("click", showAddArticleForm);
    }

    function showArtikelTable() {
      updateActiveNav(2);
      const submenuItems = document.querySelectorAll('#adminSubmenu div');
      submenuItems.forEach(item => item.classList.remove('active-item'));
      submenuItems[0].classList.add('active-item');
      loadArtikelData();
    }

    function showAddArticleForm() {
      updateActiveNav(2);
      document.getElementById("mainContent").innerHTML = addArticleContent;
      const today = new Date().toISOString().split('T')[0];
      document.getElementById('publishedDate').value = today;
      document.getElementById('articleForm').addEventListener('submit', submitArtikelForm);
    }

    function submitArtikelForm(event) {
      event.preventDefault();
      
      const formData = new FormData();
      formData.append('thumbnail', document.getElementById('thumbnail').files[0]);
      formData.append('published_date', document.getElementById('publishedDate').value);
      formData.append('source', document.getElementById('source').value);
      formData.append('title', document.getElementById('title').value);
      formData.append('description', document.getElementById('description').value);
      formData.append('link', document.getElementById('link').value);
      formData.append('_token', window.csrfToken);
      
      fetch('/admin/artikel', {
          method: 'POST',
          body: formData,
          headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
          }
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              alert(data.message);
              showArtikelTable();
          } else {
              alert('Error: ' + JSON.stringify(data.errors || data.message));
          }
      })
      .catch(error => {
          console.error('Error:', error);
          alert('Error saving artikel');
      });
    }

    // ========== VIDEO MANAGEMENT ==========

    function loadVideoData() {
      fetch('/admin/video-data', {
          method: 'GET',
          headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
          }
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              displayVideoTable(data.data);
              // Update dashboard count
              dashboardCounts.video = data.data.length;
          }
      })
      .catch(error => {
          console.error('Error:', error);
          alert('Error loading data video');
      });
    }

    function displayVideoTable(videos) {
      let tableHTML = `
          <h1 class="content-title">Kelola Video</h1>
          <button id="btn-add2" class="btn-add">+ Tambah Video</button>
          <table>
              <thead>
                  <tr>
                      <th>No</th>
                      <th>Judul</th>
                      <th>Sumber</th>
                      <th>Published Date</th>
                      <th>Link</th>
                      <th>Aksi</th>
                  </tr>
              </thead>
              <tbody>`;
      
      if (videos.length === 0) {
          tableHTML += `<tr><td colspan="6" style="text-align:center;">Tidak ada data video.</td></tr>`;
      } else {
          videos.forEach((video, index) => {
              tableHTML += `
                  <tr>
                      <td>${index + 1}</td>
                      <td>${video.title}</td>
                      <td>${video.source}</td>
                      <td>${video.published_date}</td>
                      <td><a href="${video.link}" target="_blank">Link</a></td>
                      <td>
                          <button onclick="editVideo(${video.id})" class="btn-edit">Edit</button>
                          <button onclick="deleteVideo(${video.id})" class="btn-delete">Hapus</button>
                      </td>
                  </tr>`;
          });
      }
      
      tableHTML += `</tbody></table>`;
      document.getElementById("mainContent").innerHTML = tableHTML;
      document.getElementById("btn-add2").addEventListener("click", showAddVideoForm);
    }

    function showVideoTable() {
      updateActiveNav(2);
      const submenuItems = document.querySelectorAll('#adminSubmenu div');
      submenuItems.forEach(item => item.classList.remove('active-item'));
      submenuItems[1].classList.add('active-item');
      loadVideoData();
    }

    function showAddVideoForm() {
      updateActiveNav(2);
      document.getElementById("mainContent").innerHTML = addVideoContent;
      const today = new Date().toISOString().split('T')[0];
      document.getElementById('publishedDate').value = today;
      document.getElementById('videoForm').addEventListener('submit', submitVideoForm);
    }

    function submitVideoForm(event) {
      event.preventDefault();
      
      const formData = new FormData();
      formData.append('title', document.getElementById('videoTitle').value);
      formData.append('source', document.getElementById('videoSource').value);
      formData.append('published_date', document.getElementById('publishedDate').value);
      formData.append('link', document.getElementById('videoLink').value);
      formData.append('_token', window.csrfToken);
      
      fetch('/admin/video', {
          method: 'POST',
          body: formData,
          headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
          }
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              alert(data.message);
              showVideoTable();
          } else {
              alert('Error: ' + JSON.stringify(data.errors || data.message));
          }
      })
      .catch(error => {
          console.error('Error:', error);
          alert('Error saving video');
      });
    }

    // ========== TANAMAN MANAGEMENT ==========

    let plantsData = [];

    async function loadPlantsData() {
      try {
          const response = await fetch('/api/plants', {
              headers: {
                  'Accept': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              }
          });
          
          const result = await response.json();
          
          if (result.success) {
              plantsData = result.data;
              // Update dashboard count
              dashboardCounts.tanaman = result.data.length;
              return true;
          } else {
              console.error('Failed to load plants data');
              return false;
          }
      } catch (error) {
          console.error('Error loading plants data:', error);
          return false;
      }
    }

    async function showTanamanTable() {
      updateActiveNav(2);
      const submenuItems = document.querySelectorAll('#adminSubmenu div');
      submenuItems.forEach(item => item.classList.remove('active-item'));
      submenuItems[2].classList.add('active-item');
      
      const loaded = await loadPlantsData();
      
      let tableRows = '';
      
      if (loaded && plantsData.length > 0) {
          plantsData.forEach((plant, index) => {
              tableRows += `
                  <tr>
                      <td>${index + 1}</td>
                      <td>${plant.name}</td>
                      <td>${plant.suhu}°C</td>
                      <td>${plant.description.substring(0, 50)}${plant.description.length > 50 ? '...' : ''}</td>
                      <td>
                          ${plant.image ? 
                              `<img src="/storage/plants/${plant.image}" alt="${plant.name}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">` : 
                              '<span style="color: #999;">Tidak ada gambar</span>'
                          }
                      </td>
                      <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${plant.cara_menanam ? plant.cara_menanam.replace(/\n/g, ' ') : 'Tidak ada data'}</td>
                      <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${plant.kebutuhan_lingkungan ? plant.kebutuhan_lingkungan.replace(/<[^>]*>/g, '').replace(/\n/g, ' ') : 'Tidak ada data'}</td>
                      <td>${plant.waktu_panen || 'Tidak ada data'}</td>
                      <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${plant.tips_perawatan ? plant.tips_perawatan.replace(/\n/g, ' ') : 'Tidak ada data'}</td>
                      <td>
                          <button onclick="viewTanaman(${plant.id})" class="btn-view" style="background: #17a2b8; color: white; padding: 4px 8px; border: none; border-radius: 4px; margin: 2px; cursor: pointer;">Detail</button>
                          <button onclick="editTanaman(${plant.id})" class="btn-edit" style="background: #ffc107; color: white; padding: 4px 8px; border: none; border-radius: 4px; margin: 2px; cursor: pointer;">Edit</button>
                          <button onclick="deleteTanaman(${plant.id})" class="btn-delete" style="background: #dc3545; color: white; padding: 4px 8px; border: none; border-radius: 4px; margin: 2px; cursor: pointer;">Hapus</button>
                      </td>
                  </tr>
              `;
          });
      } else {
          tableRows = '<tr><td colspan="10" style="text-align:center; color: #999;">Tidak ada data tanaman.</td></tr>';
      }
      
      document.getElementById("mainContent").innerHTML = `
          <h1 class="content-title">Kelola Tanaman</h1>
          <button id="btn-add3" class="btn-add" style="background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 4px; margin-bottom: 20px; cursor: pointer;">+ Tambah Tanaman</button>
          
          <div style="overflow-x: auto;">
              <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                  <thead>
                      <tr style="background: #f8f9fa;">
                          <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">No</th>
                          <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Nama Tanaman</th>
                          <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Suhu</th>
                          <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Deskripsi</th>
                          <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Gambar</th>
                          <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Cara Menanam</th>
                          <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Kebutuhan Lingkungan</th>
                          <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Waktu Panen</th>
                          <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Tips Perawatan</th>
                          <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6;">Aksi</th>
                      </tr>
                  </thead>
                  <tbody>
                      ${tableRows}
                  </tbody>
              </table>
          </div>
      `;
      
      document.getElementById("btn-add3").addEventListener("click", showAddTanamanForm);
    }

    function showAddTanamanForm() {
      updateActiveNav(2);
      const submenuItems = document.querySelectorAll('#adminSubmenu div');
      submenuItems.forEach(item => item.classList.remove('active-item'));
      submenuItems[2].classList.add('active-item');
      
      document.getElementById("mainContent").innerHTML = `
          <h1 class="content-title">Tambah Tanaman Baru</h1>
          
          <form id="tanamanForm" enctype="multipart/form-data" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
              <div class="form-group" style="margin-bottom: 20px;">
                  <label for="name" style="display: block; margin-bottom: 8px; font-weight: bold;">Nama Tanaman:</label>
                  <input type="text" id="name" name="name" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
              </div>
              
              <div class="form-group" style="margin-bottom: 20px;">
                  <label for="suhu" style="display: block; margin-bottom: 8px; font-weight: bold;">Suhu (°C):</label>
                  <input type="number" id="suhu" name="suhu" min="0" max="50" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
              </div>
              
              <div class="form-group" style="margin-bottom: 20px;">
                  <label for="description" style="display: block; margin-bottom: 8px; font-weight: bold;">Deskripsi:</label>
                  <textarea id="description" name="description" rows="3" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; resize: vertical;"></textarea>
              </div>
              
              <div class="form-group" style="margin-bottom: 20px;">
                  <label for="image" style="display: block; margin-bottom: 8px; font-weight: bold;">Gambar:</label>
                  <input type="file" id="image" name="image" accept="image/jpeg,image/jpg,image/png,image/gif" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
                  <small style="color: #666; display: block; margin-top: 5px;">Format yang didukung: JPG, JPEG, PNG, GIF (Max: 2MB)</small>
              </div>
              
              <div class="form-group" style="margin-bottom: 20px;">
                  <label for="cara_menanam" style="display: block; margin-bottom: 8px; font-weight: bold;">Cara Menanam:</label>
                  <textarea id="cara_menanam" name="cara_menanam" rows="5" required 
                      placeholder="Contoh:&#10;1. Siapkan benih&#10;2. Rendam dalam air&#10;3. Semai di rockwool"
                      style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; resize: vertical;"></textarea>
              </div>
              
              <div class="form-group" style="margin-bottom: 20px;">
                  <label for="kebutuhan_lingkungan" style="display: block; margin-bottom: 8px; font-weight: bold;">Kebutuhan Lingkungan:</label>
                  <textarea id="kebutuhan_lingkungan" name="kebutuhan_lingkungan" rows="4" required 
                      placeholder="Contoh:&#10;<strong>Suhu:</strong> 25-30°C&#10;<strong>Kelembaban:</strong> 60-70%"
                      style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; resize: vertical;"></textarea>
              </div>
              
              <div class="form-group" style="margin-bottom: 20px;">
                  <label for="waktu_panen" style="display: block; margin-bottom: 8px; font-weight: bold;">Waktu Panen:</label>
                  <input type="text" id="waktu_panen" name="waktu_panen" required 
                      placeholder="Contoh: 25-30 hari setelah tanam"
                      style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
              </div>
              
              <div class="form-group" style="margin-bottom: 20px;">
                  <label for="tips_perawatan" style="display: block; margin-bottom: 8px; font-weight: bold;">Tips Perawatan:</label>
                  <textarea id="tips_perawatan" name="tips_perawatan" rows="4" required 
                      placeholder="Contoh:&#10;Ganti nutrisi secara rutin&#10;Pastikan aerasi baik&#10;Pangkas daun yang layu"
                      style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; resize: vertical;"></textarea>
              </div>
              
              <div class="form-actions" style="margin-top: 30px;">
                  <button type="submit" class="btn-save" style="background: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 4px; margin-right: 10px; cursor: pointer; font-size: 14px;">Simpan</button>
                  <button type="button" onclick="showTanamanTable()" class="btn-cancel" style="background: #6c757d; color: white; padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">Batal</button>
              </div>
          </form>
      `;
      
      // Form submission handler
      document.getElementById('tanamanForm').addEventListener('submit', async function(e) {
          e.preventDefault();
          
          const submitBtn = document.querySelector('.btn-save');
          const originalText = submitBtn.textContent;
          submitBtn.textContent = 'Menyimpan...';
          submitBtn.disabled = true;
          
          try {
              const formData = new FormData();
              
              formData.append('name', document.getElementById('name').value);
              formData.append('suhu', document.getElementById('suhu').value);
              formData.append('description', document.getElementById('description').value);
              formData.append('cara_menanam', document.getElementById('cara_menanam').value);
              formData.append('kebutuhan_lingkungan', document.getElementById('kebutuhan_lingkungan').value);
              formData.append('waktu_panen', document.getElementById('waktu_panen').value);
              formData.append('tips_perawatan', document.getElementById('tips_perawatan').value);
              
              const imageFile = document.getElementById('image').files[0];
              if (imageFile) {
                  formData.append('image', imageFile);
              }
              
              const response = await fetch('/api/plants', {
                  method: 'POST',
                  headers: {
                      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                      'Accept': 'application/json'
                  },
                  body: formData
              });
              
              const result = await response.json();
              
              if (result.success || response.ok) {
                  alert('Tanaman berhasil disimpan!');
                  showTanamanTable();
              } else {
                  let errorMsg = 'Gagal menyimpan tanaman';
                  if (result.errors) {
                      errorMsg += ':\n';
                      Object.values(result.errors).forEach(error => {
                          if (Array.isArray(error)) {
                              errorMsg += '- ' + error[0] + '\n';
                          } else {
                              errorMsg += '- ' + error + '\n';
                          }
                      });
                  } else if (result.message) {
                      errorMsg += ': ' + result.message;
                  }
                  alert(errorMsg);
              }
          } catch (error) {
              console.error('Error:', error);
              alert('Terjadi kesalahan saat menyimpan data: ' + error.message);
          } finally {
              submitBtn.textContent = originalText;
              submitBtn.disabled = false;
          }
      });
    }

    // ========== STUB FUNCTIONS (to be implemented if needed) ==========
    
    function editArtikel(id) {
      alert('Edit artikel function will be implemented');
    }

    function deleteArtikel(id) {
      if (confirm('Apakah Anda yakin ingin menghapus artikel ini?')) {
        alert('Delete artikel function will be implemented');
      }
    }

    function editVideo(id) {
      alert('Edit video function will be implemented');
    }

    function deleteVideo(id) {
      if (confirm('Apakah Anda yakin ingin menghapus video ini?')) {
        alert('Delete video function will be implemented');
      }
    }

    // Replace the stub functions with these implementations

function viewTanaman(id) {
    const plant = plantsData.find(p => p.id === id);
    if (!plant) {
        alert('Data tanaman tidak ditemukan');
        return;
    }
    
    document.getElementById("mainContent").innerHTML = `
        <h1 class="content-title">Detail Tanaman</h1>
        
        <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 800px;">
            <div style="display: flex; gap: 20px; margin-bottom: 20px; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 300px;">
                    <h2 style="color: #28a745; margin-bottom: 15px;">${plant.name}</h2>
                    
                    <div style="margin-bottom: 15px;">
                        <strong style="color: #333;">Suhu Optimal:</strong>
                        <span style="background: #e9ecef; padding: 4px 8px; border-radius: 4px; margin-left: 8px;">${plant.suhu}°C</span>
                    </div>
                    
                    <div style="margin-bottom: 15px;">
                        <strong style="color: #333;">Deskripsi:</strong>
                        <p style="margin: 8px 0; line-height: 1.6; color: #666;">${plant.description}</p>
                    </div>
                </div>
                
                <div style="flex: 0 0 200px;">
                    ${plant.image ? 
                        `<img src="/storage/plants/${plant.image}" alt="${plant.name}" 
                             style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">` : 
                        `<div style="width: 100%; height: 200px; background: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #6c757d; border: 2px dashed #dee2e6;">
                            <i class="fas fa-image" style="font-size: 2em;"></i>
                         </div>`
                    }
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 30px;">
                <div>
                    <h3 style="color: #17a2b8; margin-bottom: 10px; border-bottom: 2px solid #17a2b8; padding-bottom: 5px;">
                        <i class="fas fa-seedling" style="margin-right: 8px;"></i>Cara Menanam
                    </h3>
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 6px; white-space: pre-line; line-height: 1.6;">
                        ${plant.cara_menanam || 'Tidak ada data'}
                    </div>
                </div>
                
                <div>
                    <h3 style="color: #ffc107; margin-bottom: 10px; border-bottom: 2px solid #ffc107; padding-bottom: 5px;">
                        <i class="fas fa-leaf" style="margin-right: 8px;"></i>Kebutuhan Lingkungan
                    </h3>
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 6px; line-height: 1.6;">
                        ${plant.kebutuhan_lingkungan || 'Tidak ada data'}
                    </div>
                </div>
                
                <div>
                    <h3 style="color: #dc3545; margin-bottom: 10px; border-bottom: 2px solid #dc3545; padding-bottom: 5px;">
                        <i class="fas fa-clock" style="margin-right: 8px;"></i>Waktu Panen
                    </h3>
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 6px; line-height: 1.6;">
                        ${plant.waktu_panen || 'Tidak ada data'}
                    </div>
                </div>
                
                <div>
                    <h3 style="color: #6f42c1; margin-bottom: 10px; border-bottom: 2px solid #6f42c1; padding-bottom: 5px;">
                        <i class="fas fa-heart" style="margin-right: 8px;"></i>Tips Perawatan
                    </h3>
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 6px; white-space: pre-line; line-height: 1.6;">
                        ${plant.tips_perawatan || 'Tidak ada data'}
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #dee2e6; text-align: center;">
                <button onclick="editTanaman(${plant.id})" class="btn-edit" 
                        style="background: #ffc107; color: white; padding: 12px 24px; border: none; border-radius: 4px; margin-right: 10px; cursor: pointer; font-size: 14px;">
                    <i class="fas fa-edit" style="margin-right: 8px;"></i>Edit Tanaman
                </button>
                <button onclick="showTanamanTable()" class="btn-back" 
                        style="background: #6c757d; color: white; padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">
                    <i class="fas fa-arrow-left" style="margin-right: 8px;"></i>Kembali
                </button>
            </div>
        </div>
    `;
}

function editTanaman(id) {
    const plant = plantsData.find(p => p.id === id);
    if (!plant) {
        alert('Data tanaman tidak ditemukan');
        return;
    }
    
    updateActiveNav(2);
    const submenuItems = document.querySelectorAll('#adminSubmenu div');
    submenuItems.forEach(item => item.classList.remove('active-item'));
    submenuItems[2].classList.add('active-item');
    
    document.getElementById("mainContent").innerHTML = `
        <h1 class="content-title">Edit Tanaman</h1>
        
        <form id="editTanamanForm" enctype="multipart/form-data" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="editName" style="display: block; margin-bottom: 8px; font-weight: bold;">Nama Tanaman:</label>
                <input type="text" id="editName" name="name" value="${plant.name}" required 
                       style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
            </div>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="editSuhu" style="display: block; margin-bottom: 8px; font-weight: bold;">Suhu (°C):</label>
                <input type="number" id="editSuhu" name="suhu" min="0" max="50" value="${plant.suhu}" required 
                       style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
            </div>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="editDescription" style="display: block; margin-bottom: 8px; font-weight: bold;">Deskripsi:</label>
                <textarea id="editDescription" name="description" rows="3" required 
                          style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; resize: vertical;">${plant.description}</textarea>
            </div>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="editImage" style="display: block; margin-bottom: 8px; font-weight: bold;">Gambar:</label>
                <div style="margin-bottom: 10px;">
                    ${plant.image ? 
                        `<img src="/storage/plants/${plant.image}" alt="Current image" 
                              style="width: 100px; height: 100px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                         <p style="margin: 5px 0; color: #666; font-size: 12px;">Gambar saat ini</p>` : 
                        '<p style="color: #999;">Tidak ada gambar saat ini</p>'
                    }
                </div>
                <input type="file" id="editImage" name="image" accept="image/jpeg,image/jpg,image/png,image/gif" 
                       style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
                <small style="color: #666; display: block; margin-top: 5px;">Format yang didukung: JPG, JPEG, PNG, GIF (Max: 2MB). Kosongkan jika tidak ingin mengubah gambar.</small>
            </div>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="editCaraMenanam" style="display: block; margin-bottom: 8px; font-weight: bold;">Cara Menanam:</label>
                <textarea id="editCaraMenanam" name="cara_menanam" rows="5" required 
                          style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; resize: vertical;">${plant.cara_menanam || ''}</textarea>
            </div>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="editKebutuhanLingkungan" style="display: block; margin-bottom: 8px; font-weight: bold;">Kebutuhan Lingkungan:</label>
                <textarea id="editKebutuhanLingkungan" name="kebutuhan_lingkungan" rows="4" required 
                          style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; resize: vertical;">${plant.kebutuhan_lingkungan || ''}</textarea>
            </div>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="editWaktuPanen" style="display: block; margin-bottom: 8px; font-weight: bold;">Waktu Panen:</label>
                <input type="text" id="editWaktuPanen" name="waktu_panen" value="${plant.waktu_panen || ''}" required 
                       style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
            </div>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="editTipsPerawatan" style="display: block; margin-bottom: 8px; font-weight: bold;">Tips Perawatan:</label>
                <textarea id="editTipsPerawatan" name="tips_perawatan" rows="4" required 
                          style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; resize: vertical;">${plant.tips_perawatan || ''}</textarea>
            </div>
            
            <div class="form-actions" style="margin-top: 30px;">
                <button type="submit" class="btn-update" style="background: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 4px; margin-right: 10px; cursor: pointer; font-size: 14px;">
                    <i class="fas fa-save" style="margin-right: 8px;"></i>Update
                </button>
                <button type="button" onclick="viewTanaman(${plant.id})" class="btn-cancel" style="background: #17a2b8; color: white; padding: 12px 24px; border: none; border-radius: 4px; margin-right: 10px; cursor: pointer; font-size: 14px;">
                    <i class="fas fa-eye" style="margin-right: 8px;"></i>Lihat Detail
                </button>
                <button type="button" onclick="showTanamanTable()" class="btn-cancel" style="background: #6c757d; color: white; padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">
                    <i class="fas fa-times" style="margin-right: 8px;"></i>Batal
                </button>
            </div>
        </form>
    `;
    
    // Form submission handler for edit
    document.getElementById('editTanamanForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = document.querySelector('.btn-update');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 8px;"></i>Updating...';
        submitBtn.disabled = true;
        
        try {
            const formData = new FormData();
            
            formData.append('_method', 'PUT'); // Laravel method spoofing for PUT request
            formData.append('name', document.getElementById('editName').value);
            formData.append('suhu', document.getElementById('editSuhu').value);
            formData.append('description', document.getElementById('editDescription').value);
            formData.append('cara_menanam', document.getElementById('editCaraMenanam').value);
            formData.append('kebutuhan_lingkungan', document.getElementById('editKebutuhanLingkungan').value);
            formData.append('waktu_panen', document.getElementById('editWaktuPanen').value);
            formData.append('tips_perawatan', document.getElementById('editTipsPerawatan').value);
            
            const imageFile = document.getElementById('editImage').files[0];
            if (imageFile) {
                formData.append('image', imageFile);
            }
            
            const response = await fetch(`/api/plants/${id}`, {
                method: 'POST', // Using POST with _method=PUT for file upload compatibility
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success || response.ok) {
                alert('Tanaman berhasil diupdate!');
                // Refresh the plants data
                await loadPlantsData();
                // Show the updated details
                viewTanaman(id);
            } else {
                let errorMsg = 'Gagal mengupdate tanaman';
                if (result.errors) {
                    errorMsg += ':\n';
                    Object.values(result.errors).forEach(error => {
                        if (Array.isArray(error)) {
                            errorMsg += '- ' + error[0] + '\n';
                        } else {
                            errorMsg += '- ' + error + '\n';
                        }
                    });
                } else if (result.message) {
                    errorMsg += ': ' + result.message;
                }
                alert(errorMsg);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengupdate data: ' + error.message);
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    });
}

// Enhanced delete function with better confirmation
function deleteTanaman(id) {
    const plant = plantsData.find(p => p.id === id);
    if (!plant) {
        alert('Data tanaman tidak ditemukan');
        return;
    }
    
    if (confirm(`Apakah Anda yakin ingin menghapus tanaman "${plant.name}"?\n\nTindakan ini tidak dapat dibatalkan.`)) {
        // Show loading state
        const deleteBtn = event.target;
        const originalText = deleteBtn.innerHTML;
        deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        deleteBtn.disabled = true;
        
        fetch(`/api/plants/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.success || result.message) {
                alert(`Tanaman "${plant.name}" berhasil dihapus!`);
                // Refresh the table
                showTanamanTable();
            } else {
                alert('Gagal menghapus tanaman: ' + (result.message || 'Unknown error'));
                deleteBtn.innerHTML = originalText;
                deleteBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus data: ' + error.message);
            deleteBtn.innerHTML = originalText;
            deleteBtn.disabled = false;
        });
    }
}

    function deleteTanaman(id) {
      if (confirm('Apakah Anda yakin ingin menghapus tanaman ini?')) {
        alert('Delete tanaman function will be implemented');
      }
    }
  </script>
  
  <script src="{{ asset('js/dashboard_admin.js') }}"></script>
</body>
</html>