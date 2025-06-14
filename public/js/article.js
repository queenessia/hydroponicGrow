
function scrollGrid(direction) {
  const container = document.getElementById('scrollContainer');
  const scrollAmount = 300;
  if (direction === 'left') {
    container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
  } else if (direction === 'right') {
    container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
  }
}

// // Enhanced article.js untuk menampilkan data artikel dinamis

// // Fungsi untuk load data artikel dari database
// function loadArticleData() {
//     fetch('/api/articles', {
//         method: 'GET',
//         headers: {
//             'Accept': 'application/json',
//             'Content-Type': 'application/json'
//         }
//     })
//     .then(response => {
//         if (!response.ok) {
//             throw new Error(`HTTP error! status: ${response.status}`);
//         }
//         return response.json();
//     })
//     .then(data => {
//         if (data.success) {
//             displayArticles(data.data);
//         } else {
//             console.error('Error loading articles:', data.message);
//             showNoArticlesMessage();
//         }
//     })
//     .catch(error => {
//         console.error('Error fetching articles:', error);
//         showNoArticlesMessage();
//     });
// }

// // Fungsi untuk menampilkan artikel di grid
// function displayArticles(articles) {
//     const articleGrid = document.querySelector('.article-grid');
    
//     if (!articles || articles.length === 0) {
//         showNoArticlesMessage();
//         return;
//     }
    
//     // Clear existing content
//     articleGrid.innerHTML = '';
    
//     articles.forEach(article => {
//         const articleCard = createArticleCard(article);
//         articleGrid.appendChild(articleCard);
//     });
    
//     // Update selected article info jika ada
//     if (articles.length > 0) {
//         updateSelectedArticleInfo(articles[0]);
//     }
// }

// // Fungsi untuk membuat card artikel
// function createArticleCard(article) {
//     const card = document.createElement('div');
//     card.className = 'article-card';
//     card.setAttribute('data-article-id', article.id);
    
//     // Format tanggal
//     const publishDate = formatDate(article.published_date);
    
//     // Truncate description jika terlalu panjang
//     const excerpt = truncateText(article.description, 100);
    
//     // Set thumbnail dengan fallback
//     const thumbnailUrl = article.thumbnail ? 
//         `/storage/${article.thumbnail}` : 
//         '/image/default-thumbnail.png';
    
//     card.innerHTML = `
//         <img src="${thumbnailUrl}" 
//              alt="${article.title}" 
//              class="article-image"
//              onerror="this.src='/image/default-thumbnail.png'">
//         <div class="article-details">
//             <div class="publish-date">${publishDate}</div>
//             <div class="article-title">${article.title}</div>
//             <div class="article-source">${article.source}</div>
//             <div class="article-excerpt">${excerpt}</div>
//         </div>
//     `;
    
//     // Add click event untuk membuka artikel
//     card.addEventListener('click', () => {
//         openArticle(article);
//     });
    
//     return card;
// }

// // Fungsi untuk format tanggal
// function formatDate(dateString) {
//     if (!dateString) return '';
    
//     const date = new Date(dateString);
//     const options = { 
//         year: 'numeric', 
//         month: 'long', 
//         day: 'numeric' 
//     };
    
//     return date.toLocaleDateString('id-ID', options);
// }

// // Fungsi untuk truncate text
// function truncateText(text, maxLength) {
//     if (!text) return '';
//     if (text.length <= maxLength) return text;
//     return text.substring(0, maxLength) + '...';
// }

// // Fungsi untuk menampilkan pesan tidak ada artikel
// function showNoArticlesMessage() {
//     const articleGrid = document.querySelector('.article-grid');
//     articleGrid.innerHTML = `
//         <div class="no-articles-message">
//             <i class="fas fa-newspaper"></i>
//             <h3>Belum Ada Artikel</h3>
//             <p>Artikel akan ditampilkan di sini setelah admin menambahkannya.</p>
//         </div>
//     `;
// }

// // Fungsi untuk membuka artikel
// function openArticle(article) {
//     if (article.link) {
//         window.open(article.link, '_blank');
//     } else {
//         alert('Link artikel tidak tersedia');
//     }
// }

// // Fungsi untuk update selected article info
// function updateSelectedArticleInfo(article) {
//     const selectedArticleElement = document.querySelector('.selected-article');
//     if (selectedArticleElement && article) {
//         selectedArticleElement.textContent = article.title;
//     }
// }

// // Fungsi untuk search artikel
// function searchArticles(searchTerm) {
//     if (!searchTerm.trim()) {
//         loadArticleData();
//         return;
//     }
    
//     fetch(`/api/articles/search?q=${encodeURIComponent(searchTerm)}`, {
//         method: 'GET',
//         headers: {
//             'Accept': 'application/json',
//             'Content-Type': 'application/json'
//         }
//     })
//     .then(response => response.json())
//     .then(data => {
//         if (data.success) {
//             displayArticles(data.data);
//         } else {
//             showNoArticlesMessage();
//         }
//     })
//     .catch(error => {
//         console.error('Error searching articles:', error);
//         showNoArticlesMessage();
//     });
// }

// // Fungsi untuk setup search functionality
// function setupSearch() {
//     const searchBar = document.querySelector('.search-bar');
//     const searchIcon = document.querySelector('.search-icon');
    
//     let searchTimeout;
    
//     if (searchBar) {
//         searchBar.addEventListener('input', (e) => {
//             clearTimeout(searchTimeout);
//             searchTimeout = setTimeout(() => {
//                 searchArticles(e.target.value);
//             }, 500); // Debounce 500ms
//         });
        
//         searchBar.addEventListener('keypress', (e) => {
//             if (e.key === 'Enter') {
//                 clearTimeout(searchTimeout);
//                 searchArticles(e.target.value);
//             }
//         });
//     }
    
//     if (searchIcon) {
//         searchIcon.addEventListener('click', () => {
//             const searchTerm = searchBar ? searchBar.value : '';
//             searchArticles(searchTerm);
//         });
//     }
// }

// // Fungsi untuk setup scroll arrows
// function setupScrollArrows() {
//     const leftArrow = document.querySelector('.scroll-icons .fa-chevron-left');
//     const rightArrow = document.querySelector('.scroll-icons .fa-chevron-right');
//     const articleGrid = document.querySelector('.article-grid');
    
//     if (leftArrow && rightArrow && articleGrid) {
//         leftArrow.addEventListener('click', () => {
//             articleGrid.scrollBy({
//                 left: -300,
//                 behavior: 'smooth'
//             });
//         });
        
//         rightArrow.addEventListener('click', () => {
//             articleGrid.scrollBy({
//                 left: 300,
//                 behavior: 'smooth'
//             });
//         });
//     }
// }

// // Fungsi untuk refresh data artikel
// function refreshArticleData() {
//     console.log('Refreshing article data...');
//     loadArticleData();
// }

// // Initialize ketika DOM loaded
// document.addEventListener('DOMContentLoaded', function() {
//     console.log('Article page loaded');
    
//     // Setup search functionality
//     setupSearch();
    
//     // Setup scroll arrows
//     setupScrollArrows();
    
//     // Load initial article data
//     loadArticleData();
    
//     // Optional: Setup auto-refresh setiap 5 menit
//     setInterval(refreshArticleData, 5 * 60 * 1000);
// });

// // Page transition effects (existing functionality)
// window.addEventListener('load', function() {
//     document.querySelector('.page-transition').style.transform = 'translateX(-100%)';
// });

// // Export functions for external use
// window.articleFunctions = {
//     loadArticleData,
//     refreshArticleData,
//     searchArticles
// };


// // document.addEventListener('DOMContentLoaded', function() {
// //     // Smooth horizontal transition to article page
// //     document.querySelector('.nav-links a:nth-child(1)').addEventListener('click', function(e) {
// //         e.preventDefault();
        
// //         const transitionOverlay = document.querySelector('.page-transition');
// //         transitionOverlay.style.transition = 'transform 0.5s ease-in-out';
// //         transitionOverlay.style.transform = 'translateX(0)';
        
// //         // Navigate after transition completes
// //         setTimeout(() => {
// //             window.location.href = 'home.html';
// //         }, 500);
// //     });

// //     // Handle page load animation (for when coming back from other pages)
// //     const transitionOverlay = document.querySelector('.page-transition');
    
// //     // If this is the first load (not coming from another page)
// //     if (!sessionStorage.getItem('comingFromTransition')) {
// //         transitionOverlay.style.display = 'none';
// //     } else {
// //         // We're coming from another page with transition
// //         sessionStorage.removeItem('comingFromTransition');
// //         transitionOverlay.style.transition = 'transform 0.5s ease-in-out';
// //         transitionOverlay.style.transform = 'translateX(100%)';
        
// //         // Remove overlay after animation completes
// //         setTimeout(() => {
// //             transitionOverlay.style.display = 'none';
// //         }, 500);
// //     }
// // });

// // document.addEventListener('DOMContentLoaded', function() {
// //     // Smooth horizontal transition to video page
// //     document.querySelector('.nav-links a:nth-child(3)').addEventListener('click', function(e) {
// //         e.preventDefault();
        
// //         const transitionOverlay = document.querySelector('.page-transition');
// //         transitionOverlay.style.transition = 'transform 0.5s ease-in-out';
// //         transitionOverlay.style.transform = 'translateX(0)';
        
// //         // Navigate after transition completes
// //         setTimeout(() => {
// //             window.location.href = 'video.html';
// //         }, 500);
// //     });

// //     // Handle page load animation (for when coming back from other pages)
// //     const transitionOverlay = document.querySelector('.page-transition');
    
// //     // If this is the first load (not coming from another page)
// //     if (!sessionStorage.getItem('comingFromTransition')) {
// //         transitionOverlay.style.display = 'none';
// //     } else {
// //         // We're coming from another page with transition
// //         sessionStorage.removeItem('comingFromTransition');
// //         transitionOverlay.style.transition = 'transform 0.5s ease-in-out';
// //         transitionOverlay.style.transform = 'translateX(100%)';
        
// //         // Remove overlay after animation completes
// //         setTimeout(() => {
// //             transitionOverlay.style.display = 'none';
// //         }, 500);
// //     }
// // });

// // document.addEventListener('DOMContentLoaded', function() {
// //     // Smooth horizontal transition to article page
// //     document.querySelector('.nav-links a:nth-child(4)').addEventListener('click', function(e) {
// //         e.preventDefault();
        
// //         const transitionOverlay = document.querySelector('.page-transition');
// //         transitionOverlay.style.transition = 'transform 0.5s ease-in-out';
// //         transitionOverlay.style.transform = 'translateX(0)';
        
// //         // Navigate after transition completes
// //         setTimeout(() => {
// //             window.location.href = 'sign_in.html';
// //         }, 500);
// //     });

// //     // Handle page load animation (for when coming back from other pages)
// //     const transitionOverlay = document.querySelector('.page-transition');
    
// //     // If this is the first load (not coming from another page)
// //     if (!sessionStorage.getItem('comingFromTransition')) {
// //         transitionOverlay.style.display = 'none';
// //     } else {
// //         // We're coming from another page with transition
// //         sessionStorage.removeItem('comingFromTransition');
// //         transitionOverlay.style.transition = 'transform 0.5s ease-in-out';
// //         transitionOverlay.style.transform = 'translateX(100%)';
        
// //         // Remove overlay after animation completes
// //         setTimeout(() => {
// //             transitionOverlay.style.display = 'none';
// //         }, 500);
// //     }
// // });