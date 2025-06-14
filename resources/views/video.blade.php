<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VideoPage</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Amita:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/video.css') }}">
    
    <style>
        
    </style>
</head>
<body class="page-video">
<!-- Garis hijau TETAP di "Video" -->
<body>
    <div class="search-container" style="margin-top: 50px;">
        <input type="text" class="search-bar" placeholder="Search videos..." id="searchInput">
        <i class="fas fa-search search-icon"></i>
    </div>
    
    <h1>Our Recommendation Videos</h1>
    <p class="subtitle">Panduan menanam hidroponik secara lebih interaktif dengan panduan video</p>
    
    <!-- Scroll Icons Container -->
   
        <!-- Videos container with vertical scrollbar -->
        <div class="videos-container">
            <section class="videos" id="videoContainer">
                <!-- Loading state -->
                <div class="loading" id="loadingState">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Loading videos...</p>
                </div>
            </section>
            
            <!-- Custom vertical scrollbar -->
            <div class="video-scroll-container" id="videoScrollContainer" style="display: none;">
                <div class="video-scroll-buttons">
                    <button class="video-scroll-btn" id="videoScrollUp">
                        <i class="fas fa-chevron-up"></i>
                    </button>
                </div>
                <div class="video-scroll-track" id="videoScrollTrack">
                    <div class="video-scroll-thumb" id="videoScrollThumb"></div>
                </div>
                <div class="video-scroll-buttons">
                    <button class="video-scroll-btn" id="videoScrollDown">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // CSRF Token
        window.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        
        // Variables untuk menyimpan data video
        let allVideos = [];
        let filteredVideos = [];
        
        // NEW: Variables for pagination
        let currentVideoStartIndex = 0;
        const videosPerPage = 10;
        let isVideoDragging = false;
        let videoDragStartY = 0;
        let videoThumbStartY = 0;
        
        // Fungsi untuk load data video dari server - UNCHANGED
        function loadPublicVideoData() {
            fetch('/api/videos', {  // Sesuaikan dengan route API Anda
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    allVideos = data.data || [];
                    filteredVideos = [...allVideos];
                    displayVideos(filteredVideos);
                    updateVideoScrollbar();
                } else {
                    showError('Failed to load videos');
                }
            })
            .catch(error => {
                console.error('Error loading videos:', error);
                showError('Error loading videos. Please try again later.');
            });
        }
        
        // Fungsi untuk menampilkan video cards - MODIFIED for pagination
        function displayVideos(videos) {
            const container = document.getElementById('videoContainer');
            
            if (videos.length === 0) {
                container.innerHTML = `
                    <div class="no-videos">
                        <i class="fas fa-video" style="font-size: 48px; margin-bottom: 20px;"></i>
                        <p>No videos found.</p>
                    </div>
                `;
                document.getElementById('videoScrollContainer').style.display = 'none';
                return;
            }
            
            // Show scrollbar if there are more than 10 videos
            const scrollContainer = document.getElementById('videoScrollContainer');
            if (videos.length > videosPerPage) {
                scrollContainer.style.display = 'flex';
            } else {
                scrollContainer.style.display = 'none';
                currentVideoStartIndex = 0;
            }

            // Get videos for current page (10 videos)
            const startIndex = currentVideoStartIndex;
            const endIndex = Math.min(startIndex + videosPerPage, videos.length);
            const currentVideos = videos.slice(startIndex, endIndex);
            
            const videosHTML = currentVideos.map(video => {
                const videoId = extractYouTubeId(video.link);
                const embedUrl = videoId ? `https://www.youtube.com/embed/${videoId}` : video.link;
                const formattedDate = formatDate(video.published_date);
                
                return `
                    <div class="video-card">
                        ${videoId ? 
                            `<iframe src="${embedUrl}" allowfullscreen loading="lazy"></iframe>` :
                            `<div class="external-video-link">
                                <a href="${video.link}" target="_blank">
                                    <i class="fas fa-external-link-alt" style="font-size: 20px;"></i>
                                    <p>View Video</p>
                                </a>
                            </div>`
                        }
                        <div class="video-info">
                            <h3>${escapeHtml(video.title)}</h3>
                            <p class="video-source">${escapeHtml(video.source)}</p>
                            <p class="video-date">${formattedDate}</p>
                        </div>
                    </div>
                `;
            }).join('');
            
            container.innerHTML = videosHTML;
        }
        
        // Fungsi untuk extract YouTube video ID dari URL - UNCHANGED
        function extractYouTubeId(url) {
            if (!url) return null;
            
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
            const match = url.match(regExp);
            
            return (match && match[2].length === 11) ? match[2] : null;
        }
        
        // Fungsi untuk format tanggal - UNCHANGED
        function formatDate(dateString) {
            if (!dateString) return '';
            
            const date = new Date(dateString);
            const options = { 
                year: 'numeric', 
                month: '2-digit', 
                day: '2-digit' 
            };
            
            return date.toLocaleDateString('id-ID', options);
        }
        
        // Fungsi untuk escape HTML - UNCHANGED
        function escapeHtml(text) {
            if (!text) return '';
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }
        
        // Fungsi untuk menampilkan error - UNCHANGED
        function showError(message) {
            const container = document.getElementById('videoContainer');
            container.innerHTML = `
                <div class="error">
                    <i class="fas fa-exclamation-triangle" style="font-size: 48px; margin-bottom: 20px;"></i>
                    <p>${message}</p>
                    <button onclick="loadPublicVideoData()" style="margin-top: 20px; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; transition: all 0.3s ease;">
                        Try Again
                    </button>
                </div>
            `;
        }
        
        // Fungsi untuk search/filter video - MODIFIED to reset pagination
        function filterVideos(searchTerm) {
            if (!searchTerm.trim()) {
                filteredVideos = [...allVideos];
            } else {
                filteredVideos = allVideos.filter(video => 
                    video.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
                    video.source.toLowerCase().includes(searchTerm.toLowerCase())
                );
            }
            currentVideoStartIndex = 0; // Reset to first page
            displayVideos(filteredVideos);
            updateVideoScrollbar();
        }

        // Fungsi scroll - UNCHANGED (keeping existing horizontal scroll functionality)
        function scrollVideos(direction) {
            const container = document.getElementById('videoContainer');
            const scrollAmount = 300;
            
            if (direction === 'left') {
                container.scrollLeft -= scrollAmount;
            } else {
                container.scrollLeft += scrollAmount;
            }
        }
        
        // NEW: Setup custom video scrollbar functionality
        function setupCustomVideoScrollbar() {
            const scrollUp = document.getElementById('videoScrollUp');
            const scrollDown = document.getElementById('videoScrollDown');
            const scrollTrack = document.getElementById('videoScrollTrack');
            const scrollThumb = document.getElementById('videoScrollThumb');

            // Scroll up button
            scrollUp.addEventListener('click', function() {
                if (currentVideoStartIndex > 0) {
                    currentVideoStartIndex = Math.max(0, currentVideoStartIndex - videosPerPage);
                    displayVideos(filteredVideos);
                    updateVideoScrollbar();
                }
            });

            // Scroll down button
            scrollDown.addEventListener('click', function() {
                const maxStartIndex = Math.max(0, filteredVideos.length - videosPerPage);
                if (currentVideoStartIndex < maxStartIndex) {
                    currentVideoStartIndex = Math.min(maxStartIndex, currentVideoStartIndex + videosPerPage);
                    displayVideos(filteredVideos);
                    updateVideoScrollbar();
                }
            });

            // Track click
            scrollTrack.addEventListener('click', function(e) {
                if (e.target === scrollTrack) {
                    const rect = scrollTrack.getBoundingClientRect();
                    const clickY = e.clientY - rect.top;
                    const trackHeight = rect.height;
                    const thumbHeight = scrollThumb.offsetHeight;
                    
                    const maxStartIndex = Math.max(0, filteredVideos.length - videosPerPage);
                    const scrollPercent = Math.min(1, Math.max(0, (clickY - thumbHeight/2) / (trackHeight - thumbHeight)));
                    
                    currentVideoStartIndex = Math.floor(scrollPercent * maxStartIndex / videosPerPage) * videosPerPage;
                    displayVideos(filteredVideos);
                    updateVideoScrollbar();
                }
            });

            // Thumb drag functionality
            scrollThumb.addEventListener('mousedown', function(e) {
                isVideoDragging = true;
                videoDragStartY = e.clientY;
                videoThumbStartY = scrollThumb.offsetTop;
                document.addEventListener('mousemove', handleVideoThumbDrag);
                document.addEventListener('mouseup', handleVideoThumbRelease);
                e.preventDefault();
            });

            function handleVideoThumbDrag(e) {
                if (!isVideoDragging) return;
                
                const deltaY = e.clientY - videoDragStartY;
                const newThumbTop = videoThumbStartY + deltaY;
                const trackHeight = scrollTrack.offsetHeight;
                const thumbHeight = scrollThumb.offsetHeight;
                const maxThumbTop = trackHeight - thumbHeight;
                
                const clampedTop = Math.min(maxThumbTop, Math.max(0, newThumbTop));
                const scrollPercent = clampedTop / maxThumbTop;
                
                const maxStartIndex = Math.max(0, filteredVideos.length - videosPerPage);
                currentVideoStartIndex = Math.floor(scrollPercent * maxStartIndex / videosPerPage) * videosPerPage;
                
                displayVideos(filteredVideos);
                updateVideoScrollbar();
            }

            function handleVideoThumbRelease() {
                isVideoDragging = false;
                document.removeEventListener('mousemove', handleVideoThumbDrag);
                document.removeEventListener('mouseup', handleVideoThumbRelease);
            }
        }

        // NEW: Update video scrollbar position
        function updateVideoScrollbar() {
            const scrollThumb = document.getElementById('videoScrollThumb');
            const scrollTrack = document.getElementById('videoScrollTrack');
            
            if (filteredVideos.length <= videosPerPage) {
                return;
            }
            
            const maxStartIndex = Math.max(0, filteredVideos.length - videosPerPage);
            const scrollPercent = maxStartIndex > 0 ? currentVideoStartIndex / maxStartIndex : 0;
            
            const trackHeight = scrollTrack.offsetHeight;
            const thumbHeight = scrollThumb.offsetHeight;
            const maxThumbTop = trackHeight - thumbHeight;
            
            scrollThumb.style.top = (scrollPercent * maxThumbTop) + 'px';
        }
        
        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Setup search - UNCHANGED
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    filterVideos(e.target.value);
                });
            }

            // Setup scroll buttons - UNCHANGED (keeping existing horizontal scroll)
            const scrollLeft = document.getElementById('scrollLeft');
            const scrollRight = document.getElementById('scrollRight');
            
            if (scrollLeft) {
                scrollLeft.addEventListener('click', () => scrollVideos('left'));
            }
            
            if (scrollRight) {
                scrollRight.addEventListener('click', () => scrollVideos('right'));
            }
            
            // NEW: Setup custom video scrollbar
            setupCustomVideoScrollbar();
            
            // Load data - UNCHANGED
            loadPublicVideoData();
        });
        
        // Refresh data every 5 minutes (optional) - UNCHANGED
        setInterval(loadPublicVideoData, 5 * 60 * 1000);
    </script>
</body>
</html>