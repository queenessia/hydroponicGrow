// Define dashboard content as a constant
const dashboardContent = `
  <h1 class="content-title">General Information</h1>
  
  <!-- Container untuk semua box dalam 1 baris -->
  <div class="box-container">
    <!-- Box Post -->
    <div class="box">
      <img src="/image/postUser.png" alt="Icon Post" class="box-icon">
      <div class="big-zero">0</div>
      <p style="text-align:center; margin-top:auto;">Total Post</p>
    </div>

    <!-- Box Like -->
    <div class="box">
      <img src="/image/likeUser.png" alt="Icon Like" class="box-icon">
      <div class="big-zero">0</div>
      <p style="text-align:center; margin-top:auto;">Total Like</p>
    </div>

    <!-- Box Comment -->
    <div class="box">
      <img src="/image/commentUser.png" alt="Icon Comment" class="box-icon">
      <div class="big-zero">0</div>
      <p style="text-align:center; margin-top:auto;">Total Comment</p>
    </div>
  </div>
  
  <!-- Box 4 -->
  <div class="box-wide">
    <img src="/image/bookmarkUser.png" alt="Icon 4" class="box-icon">
    <h3>Bookmarks</h3>
    <div class="wide-zero">10</div>
  </div>
  
  <!-- Box 5 -->
  <div class="box-wide">
    <img src="/image/totalPost.png" alt="Icon 5" class="box-icon">
    <h3>Post</h3>
    <div class="wide-zero">567</div>
  </div>

  <!-- Box 6 -->
  <div class="box-wide">
    <img src="/image/totalMedia.png" alt="Icon 5" class="box-icon">
    <h3>Media</h3>
    <div class="wide-zero">567</div>
  </div>`;

// Navigation setup functions
function setupNavbarNavigation() {
  // Navbar links
  const navLinks = {
    'Home': 'home.html',
    'Article': 'article.html',
    'Video': 'video.html',
    'Sharing': 'sharing.html',
    'Dashboard': 'dashboard_user.html'
  };

  // Get all navbar links
  const navElements = document.querySelectorAll('.nav-links a');
  
  navElements.forEach((link, index) => {
    const pageName = Object.keys(navLinks)[index];
    const pageUrl = navLinks[pageName];
    
    link.addEventListener('click', function(e) {
      e.preventDefault();
      navigateWithTransition(pageUrl);
    });
  });
}

function setupSidebarNavigation() {
  // Sidebar menu items
  const menuActions = {
    'showPosting': 'postHistory.html',
    'showPostingLiked': 'likeHistory.html',
    'showPostingReplied': 'commenthistory.html',
    'showPostingBookmarked': 'bookmarkhistory.html',
    'showPostingMedia': 'mediaHistory.html'
  };

  // Create functions for each menu action
  for (const [funcName, pageUrl] of Object.entries(menuActions)) {
    window[funcName] = function() {
      navigateWithTransition(pageUrl);
    };
  }
}

// Navigation with transition effect
function navigateWithTransition(url) {
  // Set flag that we're doing a transition
  sessionStorage.setItem('comingFromTransition', 'true');
  
  const transitionOverlay = document.querySelector('.page-transition');
  transitionOverlay.style.display = 'block';
  transitionOverlay.style.transition = 'transform 0.5s ease-in-out';
  transitionOverlay.style.transform = 'translateX(0)';
  
  // Navigate after transition completes
  setTimeout(() => {
    window.location.href = url;
  }, 500);
}

function initializePage() {
  // Setup navigation
  setupNavbarNavigation();
  setupSidebarNavigation();
  
  // Open drawer and show dashboard when page loads
  toggleDrawer();
  showDashboard();
  
  // Handle animation when returning to page
  const transitionOverlay = document.querySelector('.page-transition');
  
  if (!sessionStorage.getItem('comingFromTransition')) {
    transitionOverlay.style.display = 'none';
  } else {
    sessionStorage.removeItem('comingFromTransition');
    transitionOverlay.style.transition = 'transform 0.5s ease-in-out';
    transitionOverlay.style.transform = 'translateX(100%)';
    
    setTimeout(() => {
      transitionOverlay.style.display = 'none';
    }, 500);
  }
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
  updateActiveNav(4); // Dashboard is the fifth menu item (index 4)
  document.getElementById("mainContent").innerHTML = dashboardContent;
}

function showKelolaAkun() {
  document.getElementById("mainContent").innerHTML = `
    <h1 class="content-title">Kelola Akun</h1>
    <p>Account management content will go here.</p>`;
}

function logout() {
  alert('Anda akan keluar dari sistem');
  // Redirect to login page
  // window.location.href = 'login.html';
}
