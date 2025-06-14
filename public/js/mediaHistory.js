function toggleDrawer() {
    const drawer = document.getElementById("drawer");
    drawer.classList.toggle("open");
  }
  
  function toggleLike(button) {
    const icon = button.querySelector('i');
    const count = button.querySelector('span');
    button.classList.toggle('liked');
    
    if (button.classList.contains('liked')) {
      icon.classList.remove('far');
      icon.classList.add('fas');
      count.textContent = parseInt(count.textContent) + 1;
    } else {
      icon.classList.remove('fas');
      icon.classList.add('far');
      count.textContent = parseInt(count.textContent) - 1;
    }
  }
  
  // Fungsi untuk toggle drawer/sidebar
  function toggleDrawer() {
    const drawer = document.getElementById("drawer");
    drawer.classList.toggle("open");
  }
  
  // Fungsi untuk toggle like button
  function toggleLike(button) {
    button.classList.toggle('liked');
    
    const icon = button.querySelector('i');
    const count = button.querySelector('span');
    
    if (button.classList.contains('liked')) {
      icon.classList.remove('far');
      icon.classList.add('fas');
      if (count) count.textContent = parseInt(count.textContent) + 1;
    } else {
      icon.classList.remove('fas');
      icon.classList.add('far');
      if (count) count.textContent = parseInt(count.textContent) - 1;
    }
  }
  
  // Fungsi navigasi untuk navbar
  function setupNavbarNavigation() {
    // Navbar links
    const navLinks = {
     'Home': 'home.html',
      'Article': 'article.html',
      'Video': 'video.html',
      'Sharing': 'sharing.html',
      'Dashboard': 'dashboard_user.html'
    };
  
    // Set href untuk setiap link navbar
    document.querySelectorAll('.nav-links a').forEach((link, index) => {
      const linkText = Object.keys(navLinks)[index];
      link.href = navLinks[linkText];
    });
  }
  
  // Fungsi navigasi untuk sidebar
  function setupSidebarNavigation() {
    // Sidebar menu items
    const menuActions = {
      'showPosting': 'postHistory.html',
      'showPostingLiked': 'likeHistory.html',
      'showPostingReplied': 'commenthistory.html',
      'showPostingBookmarked': 'bookmarkhistory.html',
      'showPostingMedia': 'mediaHistory.html'
    };
  
    // Set onclick untuk setiap item menu sidebar
    Object.keys(menuActions).forEach(funcName => {
      // Cari elemen yang memiliki onclick dengan nama fungsi ini
      const elements = document.querySelectorAll(`[onclick*="${funcName}"]`);
      
      elements.forEach(el => {
        // Ganti fungsi onclick dengan navigasi ke halaman yang sesuai
        el.onclick = function() {
          window.location.href = menuActions[funcName];
        };
      });
    });
  
    // Menu Keluar (logout)
    const logoutBtn = document.querySelector('.submenu div:nth-child(2)');
    if (logoutBtn) {
      logoutBtn.onclick = function() {
        // Tambahkan logika logout di sini
        window.location.href = 'login.html';
      };
    }
  }
  
  // Inisialisasi saat halaman dimuat
  document.addEventListener('DOMContentLoaded', function() {
    // Setup like buttons
    document.querySelectorAll('.like-button').forEach(button => {
      button.addEventListener('click', function() {
        toggleLike(this);
      });
    });
  
    // Setup navigasi
    setupNavbarNavigation();
    setupSidebarNavigation();
  });