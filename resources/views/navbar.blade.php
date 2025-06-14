<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hydroponic Grow</title>
    <style>
     /* ====== Font Import ====== */
        @import url('https://fonts.googleapis.com/css2?family=Amita:wght@400;700&display=swap');

        /* ====== Reset & Base Styles ====== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .amita-regular {
            font-family: "Amita", serif;
            font-weight: 400;
            font-style: normal;
        }

        .amita-bold {
            font-family: "Amita", serif;
            font-weight: 700;
            font-style: normal;
        }

        /* ====== Navbar Responsive ====== */
        .transparent-nav {
            position: fixed;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            height: 70px;
            top: 0;
            left: 0;
            padding: 10px 5%;
            font-weight: bold;
            z-index: 1000;
            border-bottom: none;
            background-color: #f3efea;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .logo {
            height: 40px;
            width: auto;
        }

        .brand-name {
            font-family: "Amita", serif;
            font-weight: 700;
            font-size: clamp(16px, 4vw, 24px);
            color: black;
            white-space: nowrap;
        }

        /* ====== Navigation Links ====== */
        .nav-links {
            display: flex;
            align-items: center;
            gap: clamp(20px, 5vw, 50px);
            list-style: none;
            margin: 0;
            padding: 0;
            transition: all 0.3s ease;
        }

        .nav-links a {
            text-decoration: none;
            color: black;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 8px 16px;
            position: relative;
            font-size: clamp(14px, 2.5vw, 16px);
            border-radius: 20px;
            white-space: nowrap;
        }

        /* ====== HOVER EFFECTS - HIGHEST PRIORITY ====== */
        .nav-links a:hover {
            color: #006400 !important;
            text-decoration: none !important;
        }

        /* ====== ACTIVE STATES ====== */
        .nav-links a.active {
            color: #006400 !important;
            font-weight: 600 !important;
        }

        /* ====== Mobile Menu Button ====== */
        .mobile-menu {
            display: none;
            flex-direction: column;
            cursor: pointer;
            padding: 5px;
            z-index: 1001;
        }

        .mobile-menu span {
            width: 25px;
            height: 3px;
            background: black;
            margin: 3px 0;
            transition: 0.3s;
            border-radius: 2px;
        }

        /* Mobile Menu Animation */
        .mobile-menu.active span:nth-child(1) {
            transform: rotate(-45deg) translate(-5px, 6px);
        }

        .mobile-menu.active span:nth-child(2) {
            opacity: 0;
        }

        .mobile-menu.active span:nth-child(3) {
            transform: rotate(45deg) translate(-5px, -6px);
        }

        /* ====== Mobile Overlay ====== */
        .mobile-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .mobile-overlay.active {
            display: block;
        }

        /* ====== Media Queries ====== */
        @media (max-width: 768px) {
            .transparent-nav {
                padding: 10px 20px;
            }
            
            .nav-links {
                position: fixed;
                top: 70px;
                left: -100%;
                width: 100%;
                height: calc(100vh - 70px);
                background: white;
                flex-direction: column;
                justify-content: flex-start;
                align-items: center;
                padding-top: 50px;
                gap: 30px;
                transition: left 0.3s ease;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                z-index: 1000;
            }
            
            .nav-links.active {
                left: 0;
            }
            
            .nav-links a {
                font-size: 18px;
                padding: 15px 30px;
                width: 80%;
                text-align: center;
                border: 2px solid #f0f0f0;
                margin: 5px 0;
                border-radius: 25px;
                position: relative;
            }
            
            .nav-links a.active {
                background: linear-gradient(135deg, #006400, #32CD32) !important;
                color: white !important;
                border-color: #006400 !important;
            }
            
            .mobile-menu {
                display: flex;
            }
            
            .logo-container {
                gap: 8px;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>
    
    <nav class="transparent-nav">
        <div class="logo-container">
            <img src="/image/logo.png" alt="Logo" class="logo" onerror="this.style.display='none'">
            <div class="brand-name">Hydroponic Grow</div>
        </div>
        
        <ul class="nav-links" id="navLinks">
            <li><a href="#" id="homeLink" class="active">Home</a></li>
            <li><a href="#" id="articleLink">Article</a></li>
            <li><a href="#" id="videoLink">Video</a></li>
            <li><a href="#" id="sharingLink">Sharing</a></li>
            <li><a href="#" id="dashboardLink">Dashboard</a></li>
        </ul>
        
        <div class="mobile-menu" id="mobileMenu">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenu = document.getElementById('mobileMenu');
            const navLinks = document.getElementById('navLinks');
            const mobileOverlay = document.getElementById('mobileOverlay');
            
            // Navigation elements
            const homeLink = document.getElementById('homeLink');
            const articleLink = document.getElementById('articleLink');
            const videoLink = document.getElementById('videoLink');
            const sharingLink = document.getElementById('sharingLink');
            const dashboardLink = document.getElementById('dashboardLink');
            
            // Variable to track if user manually clicked a nav link
            let manualNavigation = false;
            
            // Setup navigation functionality
            setupSmoothScrollNavigation();
            setupActiveStateManagement();
            
            // Mobile menu functionality
            if (mobileMenu && navLinks) {
                // Toggle mobile menu
                mobileMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleMobileMenu();
                });
                
                // Close menu when clicking on navigation links
                const navLinksItems = navLinks.querySelectorAll('a');
                navLinksItems.forEach(link => {
                    link.addEventListener('click', function() {
                        closeMobileMenu();
                    });
                });
                
                // Close menu when clicking on overlay
                mobileOverlay.addEventListener('click', closeMobileMenu);
                
                // Close menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!mobileMenu.contains(event.target) && 
                        !navLinks.contains(event.target) && 
                        navLinks.classList.contains('active')) {
                        closeMobileMenu();
                    }
                });
                
                // Close menu on window resize
                window.addEventListener('resize', function() {
                    if (window.innerWidth > 768) {
                        closeMobileMenu();
                    }
                });
                
                // Close menu on Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && navLinks.classList.contains('active')) {
                        closeMobileMenu();
                    }
                });
            }
            
            function toggleMobileMenu() {
                mobileMenu.classList.toggle('active');
                navLinks.classList.toggle('active');
                mobileOverlay.classList.toggle('active');
                
                // Prevent body scroll when menu is open
                if (navLinks.classList.contains('active')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            }
            
            function closeMobileMenu() {
                mobileMenu.classList.remove('active');
                navLinks.classList.remove('active');
                mobileOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
            
            function setupSmoothScrollNavigation() {
                // Home navigation
                if (homeLink) {
                    homeLink.addEventListener('click', function(e) {
                        e.preventDefault();
                        manualNavigation = true;
                        scrollToTop();
                        setActiveLink('home');
                        // Reset manual navigation flag after scroll completes
                        setTimeout(() => { manualNavigation = false; }, 1000);
                    });
                }
                
                // Article navigation
                if (articleLink) {
                    articleLink.addEventListener('click', function(e) {
                        e.preventDefault();
                        manualNavigation = true;
                        scrollToArticleSection();
                        setActiveLink('article');
                        setTimeout(() => { manualNavigation = false; }, 1000);
                    });
                }
                
                // Video navigation
                if (videoLink) {
                    videoLink.addEventListener('click', function(e) {
                        e.preventDefault();
                        manualNavigation = true;
                        scrollToVideoSection();
                        setActiveLink('video');
                        setTimeout(() => { manualNavigation = false; }, 1000);
                    });
                }
                
                // FIXED: Sharing navigation
                if (sharingLink) {
                    sharingLink.addEventListener('click', function(e) {
                        e.preventDefault();
                        setActiveLink('sharing');
                        // Navigate to sharing page
                        window.location.href = '/sharing';
                    });
                }
                
                // FIXED: Dashboard navigation
                if (dashboardLink) {
                    dashboardLink.addEventListener('click', function(e) {
                        e.preventDefault();
                        setActiveLink('dashboard');
                        // Navigate to dashboard page
                        window.location.href = '/dashboard';
                    });
                }
            }
            
            function setupActiveStateManagement() {
                // Scroll-based active state management
                let ticking = false;
                
                window.addEventListener('scroll', function() {
                    if (!ticking && !manualNavigation) {
                        requestAnimationFrame(function() {
                            updateActiveStateOnScroll();
                            ticking = false;
                        });
                        ticking = true;
                    }
                });
            }
            
            function updateActiveStateOnScroll() {
                const scrollTop = window.scrollY;
                const windowHeight = window.innerHeight;
                
                // Get section positions
                const homeSection = document.getElementById('home');
                const articleSection = document.getElementById('articles');
                const videoSection = document.getElementById('videos');
                
                let activeSection = 'home'; // default
                
                if (articleSection) {
                    const articleTop = articleSection.offsetTop - 100;
                    const articleBottom = articleTop + articleSection.offsetHeight;
                    
                    if (scrollTop >= articleTop && scrollTop < articleBottom) {
                        activeSection = 'article';
                    }
                }
                
                if (videoSection) {
                    const videoTop = videoSection.offsetTop - 100;
                    
                    if (scrollTop >= videoTop) {
                        activeSection = 'video';
                    }
                }
                
                setActiveLink(activeSection);
            }
            
            function setActiveLink(section) {
                // Remove active class from all links
                const allLinks = document.querySelectorAll('.nav-links a');
                allLinks.forEach(link => link.classList.remove('active'));
                
                // Add active class to the appropriate link
                switch(section) {
                    case 'home':
                        homeLink?.classList.add('active');
                        break;
                    case 'article':
                        articleLink?.classList.add('active');
                        break;
                    case 'video':
                        videoLink?.classList.add('active');
                        break;
                    case 'sharing':
                        sharingLink?.classList.add('active');
                        break;
                    case 'dashboard':
                        dashboardLink?.classList.add('active');
                        break;
                }
            }
            
            function scrollToTop() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
            
            function scrollToArticleSection() {
                const articleElement = findArticleSection();
                
                if (articleElement) {
                    const navbarHeight = 70;
                    const elementPosition = articleElement.getBoundingClientRect().top + window.pageYOffset;
                    const offsetPosition = elementPosition - navbarHeight - 20;
                    
                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                } else {
                    // Fallback: scroll to middle of page
                    window.scrollTo({
                        top: window.innerHeight,
                        behavior: 'smooth'
                    });
                }
            }
            
            function scrollToVideoSection() {
                const videoElement = findVideoSection();
                
                if (videoElement) {
                    const navbarHeight = 70;
                    const elementPosition = videoElement.getBoundingClientRect().top + window.pageYOffset;
                    const offsetPosition = elementPosition - navbarHeight - 20;
                    
                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                } else {
                    // Fallback: scroll to bottom area
                    window.scrollTo({
                        top: document.body.scrollHeight - window.innerHeight,
                        behavior: 'smooth'
                    });
                }
            }
            
            function findArticleSection() {
                // Try to find article section by ID first
                let element = document.getElementById('articles');
                if (element) return element;
                
                // Search by heading text
                const headings = document.querySelectorAll('h1, h2, h3, h4');
                for (let heading of headings) {
                    if (heading.textContent.toLowerCase().includes('selected article')) {
                        return heading;
                    }
                }
                
                // Search by class names
                const selectors = [
                    '.selected-article',
                    '.articles-container', 
                    '.article-section'
                ];
                
                for (let selector of selectors) {
                    element = document.querySelector(selector);
                    if (element) return element;
                }
                
                return null;
            }
            
            function findVideoSection() {
                // Try to find video section by ID first
                let element = document.getElementById('videos');
                if (element) return element;
                
                // Search by heading text
                const headings = document.querySelectorAll('h1, h2, h3, h4');
                for (let heading of headings) {
                    const text = heading.textContent.toLowerCase();
                    if (text.includes('our recommendation videos') || text.includes('recommendation video')) {
                        return heading;
                    }
                }
                
                // Search by class names
                const selectors = [
                    '.videos-container',
                    '.video-section',
                    '.recommendation-videos'
                ];
                
                for (let selector of selectors) {
                    element = document.querySelector(selector);
                    if (element) return element;
                }
                
                return null;
            }
        });
    </script>
</body>
</html>