// Fixed Dashboard Real Data Functions with Post Management Actions
let dashboardStats = {
    total_posts: 0,
    total_likes: 0,
    total_bookmarks: 0,
    posts_liked: 0,
    posts_bookmarked: 0
};

// Helper function to get CSRF token
function getCSRFToken() {
    const token = document.querySelector('meta[name="csrf-token"]');
    return token ? token.getAttribute('content') : '';
}

// Helper function to make API calls
async function makeAPICall(url, options = {}) {
    const defaultOptions = {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            ...(options.headers || {})
        }
    };
    
    // Add CSRF token if not present
    const token = getCSRFToken();
    if (token && !defaultOptions.headers['X-CSRF-TOKEN']) {
        defaultOptions.headers['X-CSRF-TOKEN'] = token;
    }
    
    try {
        const response = await fetch(url, { ...defaultOptions, ...options });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        return { success: true, data };
        
    } catch (error) {
        console.error(`API call failed for ${url}:`, error);
        return { 
            success: false, 
            error: error.message,
            data: null 
        };
    }
}

// Load dashboard stats from API
async function loadDashboardStats() {
    try {
        showLoadingInContent();
        
        const result = await makeAPICall('/dashboard/stats');
        
        if (result.success && result.data.status === 'success') {
            dashboardStats = result.data.data;
            updateDashboardDisplay();
        } else {
            console.error('Failed to load dashboard stats:', result.error);
            showErrorInContent('Failed to load dashboard statistics. Please try again.');
        }
    } catch (error) {
        console.error('Error loading dashboard stats:', error);
        showErrorInContent('An error occurred while loading dashboard data.');
    }
}

// Update dashboard display with real data
function updateDashboardDisplay() {
    const dashboardContent = `
        <h1 class="content-title">General Information</h1>
        
        <!-- Container untuk box 1-3 -->
        <div class="box-container">
            <!-- Box 1 -->
            <div class="box" onclick="showPost()" style="cursor: pointer;">
                <img src="/image/postUser.png" alt="Icon 1" class="box-icon">
                <h3>My Posts</h3>
                <div class="big-zero">${dashboardStats.total_posts}</div>
                <small style="color: #666; font-size: 12px; margin-top: 5px; display: block;">Total posts created</small>
            </div>
            
            <!-- Box 2 -->
            <div class="box" onclick="showBookmarkTable()" style="cursor: pointer;">
                <img src="/Image/bookmarkUser.png" alt="Icon 3" class="box-icon">
                <h3>Posts I Bookmarked</h3>
                <div class="big-zero">${dashboardStats.posts_bookmarked}</div>
                <small style="color: #666; font-size: 12px; margin-top: 5px; display: block;">Posts you've bookmarked</small>
            </div>
            
            
            <!-- Box 3 -->
            <div class="box" onclick="showLikeTable()" style="cursor: pointer;">
                <img src="/image/likeUser.png" alt="Icon 2" class="box-icon">
                <h3>Posts I Liked</h3>
                <div class="big-zero">${dashboardStats.posts_liked}</div>
                <small style="color: #666; font-size: 12px; margin-top: 5px; display: block;">Posts you've liked</small>
            </div>
            
        </div>
        
        <!-- Additional Stats Row -->
        <div class="box-container" style="margin-top: 20px;">
            <!-- Box 4 -->
            <div class="box">
                 <img src="/Image/loveUser.png" alt="Icon 3" class="box-icon">
                <h3>Total Likes Received</h3>
                <div class="big-zero">${dashboardStats.total_likes}</div>
                <small style="color: #666; font-size: 12px; margin-top: 5px; display: block;">Likes on your posts</small>
            </div>
            
            <!-- Box 5 -->
            <div class="box">
                <img src="/Image/bmUser.png" alt="Icon 3" class="box-icon">
                <h3>Total Bookmarks Received</h3>
                <div class="big-zero">${dashboardStats.total_bookmarks || 0}</div>
                <small style="color: #666; font-size: 12px; margin-top: 5px; display: block;">Bookmarks on your posts</small>
            </div>
            
            <!-- Box 6 -->
            <div class="box">
                <img src="/Image/erUser.png" alt="Icon 3" class="box-icon">
                <h3>Engagement Rate</h3>
                <div class="big-zero">${calculateEngagementRate()}%</div>
                <small style="color: #666; font-size: 12px; margin-top: 5px; display: block;">Likes + bookmarks per post</small>
            </div>
        </div>`;
    
    document.getElementById("mainContent").innerHTML = dashboardContent;
}

// Calculate engagement rate
function calculateEngagementRate() {
    if (dashboardStats.total_posts === 0) return 0;
    const totalEngagement = dashboardStats.total_likes + (dashboardStats.total_bookmarks || 0);
    return Math.round((totalEngagement / dashboardStats.total_posts) * 100);
}

// Load real posts data
async function loadMyPostsData() {
    try {
        showLoadingInContent();
        
        const result = await makeAPICall('/dashboard/my-posts');
        
        if (result.success && result.data.status === 'success') {
            displayMyPosts(result.data.data.data || result.data.data);
        } else {
            console.error('Failed to load my posts:', result.error);
            showErrorInContent('Failed to load your posts. Please try again.');
        }
    } catch (error) {
        console.error('Error loading my posts:', error);
        showErrorInContent('An error occurred while loading posts.');
    }
}

// Load liked posts data
async function loadLikedPostsData() {
    try {
        showLoadingInContent();
        
        const result = await makeAPICall('/dashboard/liked-posts');
        
        if (result.success && result.data.status === 'success') {
            displayLikedPosts(result.data.data.data || result.data.data);
        } else {
            console.error('Failed to load liked posts:', result.error);
            showErrorInContent('Failed to load liked posts. Please try again.');
        }
    } catch (error) {
        console.error('Error loading liked posts:', error);
        showErrorInContent('An error occurred while loading liked posts.');
    }
}

// Load bookmarked posts data
async function loadBookmarkedPostsData() {
    try {
        showLoadingInContent();
        
        const result = await makeAPICall('/dashboard/bookmarked-posts');
        
        if (result.success && result.data.status === 'success') {
            displayBookmarkedPosts(result.data.data.data || result.data.data);
        } else {
            console.error('Failed to load bookmarked posts:', result.error);
            showErrorInContent('Failed to load bookmarked posts. Please try again.');
        }
    } catch (error) {
        console.error('Error loading bookmarked posts:', error);
        showErrorInContent('An error occurred while loading bookmarked posts.');
    }
}

// Post Management Actions
async function deletePost(postId) {
    if (!confirm('Are you sure you want to delete this post? This action cannot be undone.')) {
        return;
    }
    
    try {
        const result = await makeAPICall(`/posts/${postId}`, {
            method: 'DELETE'
        });
        
        if (result.success && result.data.success) {
            showAlert('success', 'Post deleted successfully');
            // Refresh the current view
            showPost();
            // Refresh dashboard stats
            loadDashboardStats();
        } else {
            showAlert('error', result.data?.message || 'Failed to delete post');
        }
    } catch (error) {
        console.error('Error deleting post:', error);
        showAlert('error', 'An error occurred while deleting the post');
    }
}

async function toggleLikeFromDashboard(postId) {
    try {
        const result = await makeAPICall(`/posts/${postId}/like`, {
            method: 'POST'
        });
        
        if (result.success && result.data.success) {
            const action = result.data.is_liked ? 'liked' : 'unliked';
            showAlert('success', `Post ${action} successfully`);
            // Refresh the current view
            showLikeTable();
            // Refresh dashboard stats
            loadDashboardStats();
        } else {
            showAlert('error', result.data?.message || 'Failed to update like');
        }
    } catch (error) {
        console.error('Error toggling like:', error);
        showAlert('error', 'An error occurred while updating like');
    }
}

async function toggleBookmarkFromDashboard(postId) {
    try {
        const result = await makeAPICall(`/posts/${postId}/bookmark`, {
            method: 'POST'
        });
        
        if (result.success && result.data.success) {
            const action = result.data.is_bookmarked ? 'bookmarked' : 'unbookmarked';
            showAlert('success', `Post ${action} successfully`);
            // Refresh the current view
            showBookmarkTable();
            // Refresh dashboard stats
            loadDashboardStats();
        } else {
            showAlert('error', result.data?.message || 'Failed to update bookmark');
        }
    } catch (error) {
        console.error('Error toggling bookmark:', error);
        showAlert('error', 'An error occurred while updating bookmark');
    }
}

// Display my posts with management actions
function displayMyPosts(posts) {
    let postsHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1 class="content-title">My Posts (${posts.length})</h1>
            <a href="/sharing" style="background: #764ba2; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-plus"></i>Create New Post
            </a>
        </div>
    `;
    
    if (posts.length === 0) {
        postsHTML += `
            <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 15px; margin: 20px 0;">
                <i class="fas fa-pen-alt" style="font-size: 64px; color: #667eea; opacity: 0.3; margin-bottom: 20px;"></i>
                <h3 style="color: #333; margin-bottom: 10px;">No Posts Yet</h3>
                <p style="color: #666; margin-bottom: 20px;">You haven't created any posts yet. Start sharing your hydroponics experience!</p>
                <a href="/sharing" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; display: inline-block;">
                    <i class="fas fa-plus" style="margin-right: 8px;"></i>Create Your First Post
                </a>
            </div>
        `;
    } else {
        posts.forEach(post => {
            postsHTML += createMyPostHTML(post);
        });
    }
    
    document.getElementById("mainContent").innerHTML = postsHTML;
}

// Display liked posts with unlike option
function displayLikedPosts(posts) {
    let postsHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1 class="content-title">Liked Posts (${posts.length})</h1>
            <a href="/sharing" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-heart"></i>Explore More Posts
            </a>
        </div>
    `;
    
    if (posts.length === 0) {
        postsHTML += `
            <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 15px; margin: 20px 0;">
                <i class="fas fa-heart" style="font-size: 64px; color: #e74c3c; opacity: 0.3; margin-bottom: 20px;"></i>
                <h3 style="color: #333; margin-bottom: 10px;">No Liked Posts</h3>
                <p style="color: #666; margin-bottom: 20px;">You haven't liked any posts yet. Explore and like posts you enjoy!</p>
                <a href="/sharing" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; display: inline-block;">
                    <i class="fas fa-heart" style="margin-right: 8px;"></i>Explore Posts
                </a>
            </div>
        `;
    } else {
        posts.forEach(post => {
            postsHTML += createLikedPostHTML(post);
        });
    }
    
    document.getElementById("mainContent").innerHTML = postsHTML;
}

// Display bookmarked posts with unbookmark option
function displayBookmarkedPosts(posts) {
    let postsHTML = `
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1 class="content-title">Bookmarked Posts (${posts.length})</h1>
            <a href="/sharing" style="background: linear-gradient(135deg, #f39c12 0%, #d68910 100%); color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-bookmark"></i>Explore More Posts
            </a>
        </div>
    `;
    
    if (posts.length === 0) {
        postsHTML += `
            <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 15px; margin: 20px 0;">
                <i class="fas fa-bookmark" style="font-size: 64px; color: #f39c12; opacity: 0.3; margin-bottom: 20px;"></i>
                <h3 style="color: #333; margin-bottom: 10px;">No Bookmarked Posts</h3>
                <p style="color: #666; margin-bottom: 20px;">You haven't bookmarked any posts yet. Bookmark posts to save them for later!</p>
                <a href="/sharing" style="background: linear-gradient(135deg, #f39c12 0%, #d68910 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; display: inline-block;">
                    <i class="fas fa-bookmark" style="margin-right: 8px;"></i>Explore Posts
                </a>
            </div>
        `;
    } else {
        posts.forEach(post => {
            postsHTML += createBookmarkedPostHTML(post);
        });
    }
    
    document.getElementById("mainContent").innerHTML = postsHTML;
}

// Create HTML for my posts with delete option
function createMyPostHTML(post) {
    const isReply = post.parent_id !== null;
    const replyIndicator = isReply ? `
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px; color: #667eea; font-size: 12px;">
            <i class="fas fa-reply" style="transform: scaleX(-1);"></i>
            <span>Reply to post</span>
        </div>
    ` : '';
    
    const images = post.images && post.images.length > 0 ? `
        <div class="post-images" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px; margin: 15px 0;">
            ${post.images.map(img => `<img src="/storage/${img}" alt="Post image" style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">`).join('')}
        </div>
    ` : '';
    
    const postStyle = isReply ? 'border-left: 3px solid #667eea; background: #f8f9fa; margin-left: 20px;' : '';
    
    return `
        <div class="post" style="${postStyle}">
            ${replyIndicator}
            <div class="post-header" style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <img src="${post.member?.profile_image ? '/storage/profile_images/' + post.member.profile_image : '/image/user.png'}" 
                         alt="Foto Profil" class="profile-pic">
                    <div>
                        <span class="username">${post.member?.username || 'Unknown User'}</span>
                        <div style="font-size: 12px; color: #666; margin-top: 2px;">
                            ${formatTimeAgo(post.created_at)}
                        </div>
                    </div>
                </div>
                <button onclick="deletePost(${post.id})" 
                        style="background: #e74c3c; color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 6px; font-size: 12px;"
                        onmouseover="this.style.background='#c0392b'" 
                        onmouseout="this.style.background='#e74c3c'"
                        title="Delete this post">
                    <i class="fas fa-trash"></i>Delete
                </button>
            </div>
            <div class="post-content">
                <p>${escapeHtml(post.content)}</p>
                ${images}
                <div class="post-actions" style="display: flex; gap: 15px; align-items: center; color: #666; font-size: 13px;">
                    <span style="display: flex; align-items: center; gap: 5px;">
                        <i class="fas fa-heart" style="color: ${post.is_liked ? '#e74c3c' : '#666'};"></i>
                        ${post.likes_count || 0}
                    </span>
                    <span style="display: flex; align-items: center; gap: 5px;">
                        <i class="fas fa-comment"></i>
                        ${post.replies_count || 0}
                    </span>
                    <span style="display: flex; align-items: center; gap: 5px;">
                        <i class="fas fa-bookmark" style="color: ${post.is_bookmarked ? '#f39c12' : '#666'};"></i>
                    </span>
                </div>
            </div>
        </div>
    `;
}

// Create HTML for liked posts with unlike option
function createLikedPostHTML(post) {
    const isReply = post.parent_id !== null;
    const replyIndicator = isReply ? `
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px; color: #667eea; font-size: 12px;">
            <i class="fas fa-reply" style="transform: scaleX(-1);"></i>
            <span>Reply to post</span>
        </div>
    ` : '';
    
    const images = post.images && post.images.length > 0 ? `
        <div class="post-images" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px; margin: 15px 0;">
            ${post.images.map(img => `<img src="/storage/${img}" alt="Post image" style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">`).join('')}
        </div>
    ` : '';
    
    const postStyle = isReply ? 'border-left: 3px solid #667eea; background: #f8f9fa; margin-left: 20px;' : '';
    
    return `
        <div class="post" style="${postStyle}">
            ${replyIndicator}
            <div class="post-header" style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <img src="${post.member?.profile_image ? '/storage/profile_images/' + post.member.profile_image : '/image/user.png'}" 
                         alt="Foto Profil" class="profile-pic">
                    <div>
                        <span class="username">${post.member?.username || 'Unknown User'}</span>
                        <div style="font-size: 12px; color: #666; margin-top: 2px;">
                            ${formatTimeAgo(post.created_at)}
                        </div>
                    </div>
                </div>
                <button onclick="toggleLikeFromDashboard(${post.id})" 
                        style="background: #e74c3c; color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 6px; font-size: 12px;"
                        onmouseover="this.style.background='#c0392b'" 
                        onmouseout="this.style.background='#e74c3c'"
                        title="Unlike this post">
                    <i class="fas fa-heart-broken"></i>Unlike
                </button>
            </div>
            <div class="post-content">
                <p>${escapeHtml(post.content)}</p>
                ${images}
                <div class="post-actions" style="display: flex; gap: 15px; align-items: center; color: #666; font-size: 13px;">
                    <span style="display: flex; align-items: center; gap: 5px;">
                        <i class="fas fa-heart" style="color: #e74c3c;"></i>
                        ${post.likes_count || 0}
                    </span>
                    <span style="display: flex; align-items: center; gap: 5px;">
                        <i class="fas fa-comment"></i>
                        ${post.replies_count || 0}
                    </span>
                    <span style="display: flex; align-items: center; gap: 5px;">
                        <i class="fas fa-bookmark" style="color: ${post.is_bookmarked ? '#f39c12' : '#666'};"></i>
                    </span>
                </div>
            </div>
        </div>
    `;
}

// Create HTML for bookmarked posts with unbookmark option
function createBookmarkedPostHTML(post) {
    const isReply = post.parent_id !== null;
    const replyIndicator = isReply ? `
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px; color: #667eea; font-size: 12px;">
            <i class="fas fa-reply" style="transform: scaleX(-1);"></i>
            <span>Reply to post</span>
        </div>
    ` : '';
    
    const images = post.images && post.images.length > 0 ? `
        <div class="post-images" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px; margin: 15px 0;">
            ${post.images.map(img => `<img src="/storage/${img}" alt="Post image" style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">`).join('')}
        </div>
    ` : '';
    
    const postStyle = isReply ? 'border-left: 3px solid #667eea; background: #f8f9fa; margin-left: 20px;' : '';
    
    return `
        <div class="post" style="${postStyle}">
            ${replyIndicator}
            <div class="post-header" style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <img src="${post.member?.profile_image ? '/storage/profile_images/' + post.member.profile_image : '/image/user.png'}" 
                         alt="Foto Profil" class="profile-pic">
                    <div>
                        <span class="username">${post.member?.username || 'Unknown User'}</span>
                        <div style="font-size: 12px; color: #666; margin-top: 2px;">
                            ${formatTimeAgo(post.created_at)}
                        </div>
                    </div>
                </div>
                <button onclick="toggleBookmarkFromDashboard(${post.id})" 
                        style="background: #f39c12; color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 6px; font-size: 12px;"
                        onmouseover="this.style.background='#d68910'" 
                        onmouseout="this.style.background='#f39c12'"
                        title="Remove bookmark">
                    <i class="fas fa-bookmark-slash"></i>Unbookmark
                </button>
            </div>
            <div class="post-content">
                <p>${escapeHtml(post.content)}</p>
                ${images}
                <div class="post-actions" style="display: flex; gap: 15px; align-items: center; color: #666; font-size: 13px;">
                    <span style="display: flex; align-items: center; gap: 5px;">
                        <i class="fas fa-heart" style="color: ${post.is_liked ? '#e74c3c' : '#666'};"></i>
                        ${post.likes_count || 0}
                    </span>
                    <span style="display: flex; align-items: center; gap: 5px;">
                        <i class="fas fa-comment"></i>
                        ${post.replies_count || 0}
                    </span>
                    <span style="display: flex; align-items: center; gap: 5px;">
                        <i class="fas fa-bookmark" style="color: #f39c12;"></i>
                    </span>
                </div>
            </div>
        </div>
    `;
}

// Show alert function
function showAlert(type, message) {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.dashboard-alert');
    existingAlerts.forEach(alert => alert.remove());

    const alert = document.createElement('div');
    alert.className = `dashboard-alert`;
    alert.style.cssText = `
        position: fixed; 
        top: 20px; 
        right: 20px; 
        padding: 15px 20px; 
        border-radius: 8px; 
        z-index: 10001;
        background: ${type === 'success' ? '#d4edda' : '#f8d7da'};
        color: ${type === 'success' ? '#155724' : '#721c24'};
        border: 1px solid ${type === 'success' ? '#c3e6cb' : '#f5c6cb'};
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        min-width: 300px;
    `;
    
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    alert.innerHTML = `
        <i class="fas ${icon}"></i>
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" style="background: none; border: none; color: inherit; cursor: pointer; margin-left: auto; font-size: 18px;">&times;</button>
    `;

    document.body.appendChild(alert);
    setTimeout(() => alert.remove(), 5000);
}

// Utility functions
function formatTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffTime = Math.abs(now - date);
    const diffMinutes = Math.floor(diffTime / (1000 * 60));
    const diffHours = Math.floor(diffTime / (1000 * 60 * 60));
    const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

    if (diffMinutes < 1) return 'Just now';
    if (diffMinutes < 60) return `${diffMinutes}m ago`;
    if (diffHours < 24) return `${diffHours}h ago`;
    if (diffDays < 7) return `${diffDays}d ago`;
    return date.toLocaleDateString();
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

function showLoadingInContent() {
    document.getElementById("mainContent").innerHTML = `
        <div style="text-align: center; padding: 60px 20px;">
            <div style="width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #667eea; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 20px;"></div>
            <p style="color: #666;">Loading data...</p>
        </div>
        <style>
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>
    `;
}

function showErrorInContent(message) {
    document.getElementById("mainContent").innerHTML = `
        <div style="text-align: center; padding: 60px 20px; background: #fee; border-radius: 15px; margin: 20px 0;">
            <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #e74c3c; margin-bottom: 20px;"></i>
            <h3 style="color: #e74c3c; margin-bottom: 10px;">Error</h3>
            <p style="color: #666; margin-bottom: 20px;">${message}</p>
            <button onclick="showDashboard()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer;">
                <i class="fas fa-refresh" style="margin-right: 8px;"></i>Try Again
            </button>
        </div>
    `;
}

// Updated functions to use new API calls
function showDashboard() {
    updateActiveNav(1);
    
    // Kosongkan submenu aktif jika ada
    const submenuItems = document.querySelectorAll('.submenu div');
    submenuItems.forEach(item => item.classList.remove('active-item'));
    
    // Load real dashboard stats
    loadDashboardStats();
}

function showPost() {
    // Set menu Kelola Postingan sebagai aktif
    updateActiveNav(2);
    
    // Set submenu Post sebagai aktif
    const submenuItems = document.querySelectorAll('#adminSubmenu div');
    submenuItems.forEach(item => item.classList.remove('active-item'));
    submenuItems[0].classList.add('active-item');
    
    // Load real posts data
    loadMyPostsData();
}

function showBookmarkTable() {
    updateActiveNav(2);
    const submenuItems = document.querySelectorAll('#adminSubmenu div');
    submenuItems.forEach(item => item.classList.remove('active-item'));
    submenuItems[1].classList.add('active-item');
    
    // Load real bookmarked posts data
    loadBookmarkedPostsData();
}

function showLikeTable() {
    updateActiveNav(2);
    const submenuItems = document.querySelectorAll('#adminSubmenu div');
    submenuItems.forEach(item => item.classList.remove('active-item'));
    submenuItems[2].classList.add('active-item');
    
    // Load real liked posts data
    loadLikedPostsData();
}

// Debug function to check API endpoints
async function debugAPI() {
    console.log('Debugging API endpoints...');
    
    // Test dashboard stats
    const statsResult = await makeAPICall('/dashboard/stats');
    console.log('Stats API result:', statsResult);
    
    // Test my posts
    const postsResult = await makeAPICall('/dashboard/my-posts');
    console.log('My Posts API result:', postsResult);
    
    // Test liked posts
    const likedResult = await makeAPICall('/dashboard/liked-posts');
    console.log('Liked Posts API result:', likedResult);
    
    // Test bookmarked posts
    const bookmarkedResult = await makeAPICall('/dashboard/bookmarked-posts');
    console.log('Bookmarked Posts API result:', bookmarkedResult);
}

// Initialize page with real data
function initializePage() {
    // Buka drawer dan tampilkan dashboard saat halaman dimuat
    toggleDrawer();
    showDashboard(); // This will now load real data
    
    // Debug API in development (remove in production)
    // debugAPI();
}