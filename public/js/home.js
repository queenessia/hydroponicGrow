// document.addEventListener('DOMContentLoaded', function() {
//     // Smooth horizontal transition to video page
//     document.querySelector('.nav-links a:nth-child(2)').addEventListener('click', function(e) {
//         e.preventDefault();
        
//         const transitionOverlay = document.querySelector('.page-transition');
//         transitionOverlay.style.transition = 'transform 0.5s ease-in-out';
//         transitionOverlay.style.transform = 'translateX(0)';
        
//         // Navigate after transition completes
//         setTimeout(() => {
//             window.location.href = 'article.html';
//         }, 500);
//     });

//     // Handle page load animation (for when coming back from other pages)
//     const transitionOverlay = document.querySelector('.page-transition');
    
//     // If this is the first load (not coming from another page)
//     if (!sessionStorage.getItem('comingFromTransition')) {
//         transitionOverlay.style.display = 'none';
//     } else {
//         // We're coming from another page with transition
//         sessionStorage.removeItem('comingFromTransition');
//         transitionOverlay.style.transition = 'transform 0.5s ease-in-out';
//         transitionOverlay.style.transform = 'translateX(100%)';
        
//         // Remove overlay after animation completes
//         setTimeout(() => {
//             transitionOverlay.style.display = 'none';
//         }, 500);
//     }
// });

// document.addEventListener('DOMContentLoaded', function() {
//     // Smooth horizontal transition to video page
//     document.querySelector('.nav-links a:nth-child(3)').addEventListener('click', function(e) {
//         e.preventDefault();
        
//         const transitionOverlay = document.querySelector('.page-transition');
//         transitionOverlay.style.transition = 'transform 0.5s ease-in-out';
//         transitionOverlay.style.transform = 'translateX(0)';
        
//         // Navigate after transition completes
//         setTimeout(() => {
//             window.location.href = 'video.html';
//         }, 500);
//     });

//     // Handle page load animation (for when coming back from other pages)
//     const transitionOverlay = document.querySelector('.page-transition');
    
//     // If this is the first load (not coming from another page)
//     if (!sessionStorage.getItem('comingFromTransition')) {
//         transitionOverlay.style.display = 'none';
//     } else {
//         // We're coming from another page with transition
//         sessionStorage.removeItem('comingFromTransition');
//         transitionOverlay.style.transition = 'transform 0.5s ease-in-out';
//         transitionOverlay.style.transform = 'translateX(100%)';
        
//         // Remove overlay after animation completes
//         setTimeout(() => {
//             transitionOverlay.style.display = 'none';
//         }, 500);
//     }
// });

// document.addEventListener('DOMContentLoaded', function() {
//     // Smooth horizontal transition to video page
//     document.querySelector('.nav-links a:nth-child(4)').addEventListener('click', function(e) {
//         e.preventDefault();
        
//         const transitionOverlay = document.querySelector('.page-transition');
//         transitionOverlay.style.transition = 'transform 0.5s ease-in-out';
//         transitionOverlay.style.transform = 'translateX(0)';
        
//         // Navigate after transition completes
//         setTimeout(() => {
//             window.location.href = 'sharing.html';
//         }, 500);
//     });

//     // Handle page load animation (for when coming back from other pages)
//     const transitionOverlay = document.querySelector('.page-transition');
    
//     // If this is the first load (not coming from another page)
//     if (!sessionStorage.getItem('comingFromTransition')) {
//         transitionOverlay.style.display = 'none';
//     } else {
//         // We're coming from another page with transition
//         sessionStorage.removeItem('comingFromTransition');
//         transitionOverlay.style.transition = 'transform 0.5s ease-in-out';
//         transitionOverlay.style.transform = 'translateX(100%)';
        
//         // Remove overlay after animation completes
//         setTimeout(() => {
//             transitionOverlay.style.display = 'none';
//         }, 500);
//     }
// });
