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
  
  // Set up navigation for the navbar
  function setupNavbarNavigation() {
    const navLinks = {
      'homeLink': 'home.html',
      'articleLink': 'article.html',
      'videoLink': 'video.html',
      'sharingLink': 'sharing.html',
      'dashboardLink': 'dashboard_user.html'
    };
  
    // Loop through each link and set the href attribute
    Object.keys(navLinks).forEach(id => {
      document.getElementById(id).href = navLinks[id];
    });
  }
  
  // Set up navigation for the sidebar
  function setupSidebarNavigation() {
    const menuActions = {
      'showPosting': 'postHistory.html',
      'showPostingLiked': 'likeHistory.html',
      'showPostingReplied': 'commenthistory.html',
      'showPostingBookmarked': 'bookmarkhistory.html',
      'showPostingMedia': 'mediaHistory.html'
    };
  
    Object.keys(menuActions).forEach(funcName => {
      const elements = document.querySelectorAll(`[onclick*="${funcName}"]`);
      elements.forEach(el => {
        el.onclick = function() {
          window.location.href = menuActions[funcName];
        };
      });
    });
  
    const logoutBtn = document.querySelector('.submenu div:nth-child(2)');
    if (logoutBtn) {
      logoutBtn.onclick = function() {
        window.location.href = 'login.html';
      };
    }
  }
  
  // Initialize navigation setup
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.like-button').forEach(button => {
      button.addEventListener('click', function() {
        toggleLike(this);
      });
    });
  
    setupNavbarNavigation();
    setupSidebarNavigation();
  });
  