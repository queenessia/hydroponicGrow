<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>ArtikelPage</title>

    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Amita:wght@400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('css/article.css') }}">
    
    <style>
        
    </style>
</head>
<body class="page-article">
<!-- Garis hijau TETAP di "Article" -->
<body>
    <!-- Transition overlay -->
    <div class="page-transition"></div>

    <div class="search-container">
        <input type="text" class="search-bar" id="searchInput" placeholder="Search Article...">
        <i class="fas fa-search search-icon"></i>
    </div> 

    <div class="selected-article">Selected Article</div>

    <div class="sub-text">
        <span>Temukan tata cara budidaya tanaman hidroponik dengan cara yang tepat</span>
    </div>

    <!-- Articles container with scrollbar -->
    <div class="articles-container">
        <div class="article-grid" id="articleGrid">
            <!-- Loading spinner -->
            <div class="loading-spinner" id="loadingSpinner">
                <i class="fas fa-spinner fa-spin"></i>
                <span style="margin-left: 10px;">Memuat artikel...</span>
            </div>
        </div>
        
        <!-- Custom scrollbar -->
        <div class="scroll-container" id="scrollContainer" style="display: none;">
            <div class="scroll-buttons">
                <button class="scroll-btn" id="scrollUp">
                    <i class="fas fa-chevron-up"></i>
                </button>
            </div>
            <div class="scroll-track" id="scrollTrack">
                <div class="scroll-thumb" id="scrollThumb"></div>
            </div>
            <div class="scroll-buttons">
                <button class="scroll-btn" id="scrollDown">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
        // Global variables
        let allArticles = [];
        let filteredArticles = [];
        let currentPage = 0;
        const articlesPerPage = 10; // Changed to 10 for grid display
        let currentStartIndex = 0;
        let isDragging = false;
        let dragStartY = 0;
        let thumbStartY = 0;

        // Load articles when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadArticles();
            setupSearchFunctionality();
            setupScrollFunctionality();
            setupCustomScrollbar();
        });

        // Function to load articles from database - UNCHANGED
        async function loadArticles() {
            try {
                showLoading(true);
                
                // UPDATED: Use public API endpoint instead of admin-only endpoint
                const response = await fetch('/api/articles', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                
                if (data.success) {
                    allArticles = data.data || [];
                    filteredArticles = [...allArticles];
                    displayArticles();
                    updateScrollbar();
                } else {
                    throw new Error(data.message || 'Failed to load articles');
                }
            } catch (error) {
                console.error('Error loading articles:', error);
                showError('Gagal memuat artikel. Silakan coba lagi.');
            } finally {
                showLoading(false);
            }
        }

        // Function to display articles - MODIFIED for pagination
        function displayArticles(articles = filteredArticles) {
            const articleGrid = document.getElementById('articleGrid');
            
            if (articles.length === 0) {
                articleGrid.innerHTML = '<div class="no-articles">Tidak ada artikel yang ditemukan.</div>';
                document.getElementById('scrollContainer').style.display = 'none';
                return;
            }

            // Show scrollbar if there are more than 10 articles
            const scrollContainer = document.getElementById('scrollContainer');
            if (articles.length > articlesPerPage) {
                scrollContainer.style.display = 'flex';
            } else {
                scrollContainer.style.display = 'none';
                currentStartIndex = 0;
            }

            // Get articles for current page (10 articles)
            const startIndex = currentStartIndex;
            const endIndex = Math.min(startIndex + articlesPerPage, articles.length);
            const currentArticles = articles.slice(startIndex, endIndex);

            let articlesHTML = '';
            currentArticles.forEach(article => {
                const thumbnailUrl = article.thumbnail ? 
                    `/storage/${article.thumbnail}` : 
                    '/image/default-thumbnail.png';
                
                const publishDate = formatDate(article.published_date);
                const excerpt = article.description ? 
                    article.description.substring(0, 150) + '...' : 
                    'Tidak ada deskripsi tersedia';

                articlesHTML += `
                    <div class="article-card" onclick="openArticle('${article.link}')">
                        <img src="${thumbnailUrl}" alt="${article.title}" class="article-image" onerror="this.src='/image/default-thumbnail.png'">
                        <div class="article-details">
                            <div class="publish-date">${publishDate}</div>
                            <div class="article-title">${article.title}</div>
                            <div class="article-source">${article.source}</div>
                            <div class="article-excerpt">${excerpt}</div>
                        </div>
                    </div>
                `;
            });

            articleGrid.innerHTML = articlesHTML;
        }

        // Function to format date - UNCHANGED
        function formatDate(dateString) {
            if (!dateString) return 'Tanggal tidak tersedia';
            
            const date = new Date(dateString);
            const options = { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            };
            return date.toLocaleDateString('id-ID', options);
        }

        // Function to open article - UNCHANGED
        function openArticle(link) {
            if (link) {
                window.open(link, '_blank');
            }
        }

        // Function to show loading state - UNCHANGED
        function showLoading(show) {
            const loadingSpinner = document.getElementById('loadingSpinner');
            if (show) {
                loadingSpinner.style.display = 'flex';
            } else {
                loadingSpinner.style.display = 'none';
            }
        }

        // Function to show error message - UNCHANGED
        function showError(message) {
            const articleGrid = document.getElementById('articleGrid');
            articleGrid.innerHTML = `<div class="error-message">${message}</div>`;
        }

        // Setup search functionality - UNCHANGED
        function setupSearchFunctionality() {
            const searchInput = document.getElementById('searchInput');
            let searchTimeout;

            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const searchTerm = this.value.toLowerCase().trim();
                    filterArticles(searchTerm);
                }, 300);
            });

            // Search on enter key
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const searchTerm = this.value.toLowerCase().trim();
                    filterArticles(searchTerm);
                }
            });

            // Search icon click
            document.querySelector('.search-icon').addEventListener('click', function() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                filterArticles(searchTerm);
            });
        }

        // Function to filter articles - MODIFIED to reset pagination
        function filterArticles(searchTerm) {
            if (!searchTerm) {
                filteredArticles = [...allArticles];
            } else {
                filteredArticles = allArticles.filter(article => 
                    article.title.toLowerCase().includes(searchTerm) ||
                    article.description.toLowerCase().includes(searchTerm) ||
                    article.source.toLowerCase().includes(searchTerm)
                );
            }
            currentStartIndex = 0; // Reset to first page
            displayArticles();
            updateScrollbar();
        }

        // Setup scroll functionality - UNCHANGED (keeping existing functionality)
        function setupScrollFunctionality() {
            const scrollLeft = document.getElementById('scrollLeft');
            const scrollRight = document.getElementById('scrollRight');
            const articleGrid = document.getElementById('articleGrid');

            if (scrollLeft) {
                scrollLeft.addEventListener('click', function() {
                    articleGrid.scrollBy({
                        left: -300,
                        behavior: 'smooth'
                    });
                });
            }

            if (scrollRight) {
                scrollRight.addEventListener('click', function() {
                    articleGrid.scrollBy({
                        left: 300,
                        behavior: 'smooth'
                    });
                });
            }

            // Show/hide scroll icons based on scroll position
            function updateScrollIcons() {
                if (!articleGrid) return;
                
                const scrollLeft = articleGrid.scrollLeft;
                const scrollWidth = articleGrid.scrollWidth;
                const clientWidth = articleGrid.clientWidth;
                
                const leftIcon = document.getElementById('scrollLeft');
                const rightIcon = document.getElementById('scrollRight');
                
                if (leftIcon) {
                    // Show/hide left icon
                    if (scrollLeft <= 0) {
                        leftIcon.style.opacity = '0.5';
                    } else {
                        leftIcon.style.opacity = '1';
                    }
                }
                
                if (rightIcon) {
                    // Show/hide right icon
                    if (scrollLeft >= scrollWidth - clientWidth - 10) {
                        rightIcon.style.opacity = '0.5';
                    } else {
                        rightIcon.style.opacity = '1';
                    }
                }
            }

            // Update icons on scroll
            if (articleGrid) {
                articleGrid.addEventListener('scroll', updateScrollIcons);
                
                // Initial update
                setTimeout(updateScrollIcons, 100);
            }
        }

        // NEW: Setup custom scrollbar functionality
        function setupCustomScrollbar() {
            const scrollUp = document.getElementById('scrollUp');
            const scrollDown = document.getElementById('scrollDown');
            const scrollTrack = document.getElementById('scrollTrack');
            const scrollThumb = document.getElementById('scrollThumb');

            // Scroll up button
            scrollUp.addEventListener('click', function() {
                if (currentStartIndex > 0) {
                    currentStartIndex = Math.max(0, currentStartIndex - articlesPerPage);
                    displayArticles();
                    updateScrollbar();
                }
            });

            // Scroll down button
            scrollDown.addEventListener('click', function() {
                const maxStartIndex = Math.max(0, filteredArticles.length - articlesPerPage);
                if (currentStartIndex < maxStartIndex) {
                    currentStartIndex = Math.min(maxStartIndex, currentStartIndex + articlesPerPage);
                    displayArticles();
                    updateScrollbar();
                }
            });

            // Track click
            scrollTrack.addEventListener('click', function(e) {
                if (e.target === scrollTrack) {
                    const rect = scrollTrack.getBoundingClientRect();
                    const clickY = e.clientY - rect.top;
                    const trackHeight = rect.height;
                    const thumbHeight = scrollThumb.offsetHeight;
                    
                    const maxStartIndex = Math.max(0, filteredArticles.length - articlesPerPage);
                    const scrollPercent = Math.min(1, Math.max(0, (clickY - thumbHeight/2) / (trackHeight - thumbHeight)));
                    
                    currentStartIndex = Math.floor(scrollPercent * maxStartIndex / articlesPerPage) * articlesPerPage;
                    displayArticles();
                    updateScrollbar();
                }
            });

            // Thumb drag functionality
            scrollThumb.addEventListener('mousedown', function(e) {
                isDragging = true;
                dragStartY = e.clientY;
                thumbStartY = scrollThumb.offsetTop;
                document.addEventListener('mousemove', handleThumbDrag);
                document.addEventListener('mouseup', handleThumbRelease);
                e.preventDefault();
            });

            function handleThumbDrag(e) {
                if (!isDragging) return;
                
                const deltaY = e.clientY - dragStartY;
                const newThumbTop = thumbStartY + deltaY;
                const trackHeight = scrollTrack.offsetHeight;
                const thumbHeight = scrollThumb.offsetHeight;
                const maxThumbTop = trackHeight - thumbHeight;
                
                const clampedTop = Math.min(maxThumbTop, Math.max(0, newThumbTop));
                const scrollPercent = clampedTop / maxThumbTop;
                
                const maxStartIndex = Math.max(0, filteredArticles.length - articlesPerPage);
                currentStartIndex = Math.floor(scrollPercent * maxStartIndex / articlesPerPage) * articlesPerPage;
                
                displayArticles();
                updateScrollbar();
            }

            function handleThumbRelease() {
                isDragging = false;
                document.removeEventListener('mousemove', handleThumbDrag);
                document.removeEventListener('mouseup', handleThumbRelease);
            }
        }

        // NEW: Update scrollbar position
        function updateScrollbar() {
            const scrollThumb = document.getElementById('scrollThumb');
            const scrollTrack = document.getElementById('scrollTrack');
            
            if (filteredArticles.length <= articlesPerPage) {
                return;
            }
            
            const maxStartIndex = Math.max(0, filteredArticles.length - articlesPerPage);
            const scrollPercent = maxStartIndex > 0 ? currentStartIndex / maxStartIndex : 0;
            
            const trackHeight = scrollTrack.offsetHeight;
            const thumbHeight = scrollThumb.offsetHeight;
            const maxThumbTop = trackHeight - thumbHeight;
            
            scrollThumb.style.top = (scrollPercent * maxThumbTop) + 'px';
        }

        // Function to refresh articles (can be called from outside) - UNCHANGED
        function refreshArticles() {
            loadArticles();
        }
    </script>
</body>
</html>