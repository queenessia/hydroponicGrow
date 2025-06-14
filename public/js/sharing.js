// Clean sharing.js - With Delete Functionality
console.log('Loading sharing.js clean version...');

class SharingManager {
    constructor() {
        console.log('SharingManager constructor called');
        
        this.currentUser = window.APP_CONFIG?.currentUser || null;
        this.userType = window.APP_CONFIG?.userType || 'Admin';
        this.isAuthenticated = window.APP_CONFIG?.isAuthenticated || false;
        this.posts = [];
        this.selectedImages = [];
        this.selectedReplyImages = []; // Add reply images array
        
        console.log('SharingManager initialized with:', {
            userType: this.userType,
            isAuthenticated: this.isAuthenticated,
            currentUser: this.currentUser
        });
        
        this.init();
    }

    init() {
        console.log('SharingManager init() called');
        this.setupCSRF();
        this.setupEventListeners();
        this.loadPosts();
    }

    setupCSRF() {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.csrfToken = token.getAttribute('content');
            console.log('CSRF token set');
        } else {
            console.error('CSRF token not found!');
        }
    }

    setupEventListeners() {
        console.log('Setting up event listeners...');
        
        // Create post button
        const createPostBtn = document.getElementById('create-post-btn');
        if (createPostBtn) {
            createPostBtn.addEventListener('click', (e) => {
                console.log('Create post button clicked!');
                e.preventDefault();
                this.showPostModal();
            });
        }

        // Modal close buttons
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('close-modal')) {
                this.closeModals();
            }
            if (e.target.classList.contains('modal-overlay')) {
                this.closeModals();
            }
            if (e.target.closest('.btn-cancel')) {
                this.closeModals();
            }
        });

        // Form submissions
        const postForm = document.getElementById('post-form');
        if (postForm) {
            postForm.addEventListener('submit', (e) => {
                console.log('Post form submitted');
                e.preventDefault();
                this.submitPost(e.target);
            });
        }

        const replyForm = document.getElementById('reply-form');
        if (replyForm) {
            replyForm.addEventListener('submit', (e) => {
                console.log('Reply form submitted');
                e.preventDefault();
                this.submitReply(e.target);
            });
        }

        // File input for posts
        const postImages = document.getElementById('post-images');
        if (postImages) {
            postImages.addEventListener('change', (e) => {
                this.handleImageSelection(e, 'post');
            });
        }

        // File input for replies
        const replyImages = document.getElementById('reply-images');
        if (replyImages) {
            replyImages.addEventListener('change', (e) => {
                this.handleImageSelection(e, 'reply');
            });
        }

        // Character counter
        const postContent = document.getElementById('post-content');
        if (postContent) {
            postContent.addEventListener('input', () => {
                this.updateCharCount();
            });
        }

        // Post actions (like, comment, bookmark, delete)
        document.addEventListener('click', (e) => {
            const actionItem = e.target.closest('.action-item');
            if (!actionItem) return;

            const post = actionItem.closest('.post');
            if (!post) return;

            const postId = post.dataset.postId;
            const action = actionItem.dataset.action;

            console.log('Action clicked:', { action, postId });

            if (action === 'like') {
                e.preventDefault();
                this.toggleLike(postId, actionItem);
            } else if (action === 'comment') {
                e.preventDefault();
                this.showReplyModal(postId);
            } else if (action === 'bookmark') {
                e.preventDefault();
                this.toggleBookmark(postId, actionItem);
            } else if (action === 'delete') {
                e.preventDefault();
                this.confirmDeletePost(postId);
            }
        });

        // Hover effects
        document.addEventListener('mouseover', (e) => {
            const actionItem = e.target.closest('.action-item');
            if (actionItem) {
                const action = actionItem.dataset.action;
                if (action === 'delete') {
                    actionItem.style.backgroundColor = 'rgba(231, 76, 60, 0.1)';
                } else {
                    actionItem.style.backgroundColor = 'rgba(102, 126, 234, 0.1)';
                }
            }
        });

        document.addEventListener('mouseout', (e) => {
            const actionItem = e.target.closest('.action-item');
            if (actionItem) {
                actionItem.style.backgroundColor = 'transparent';
            }
        });
    }

    showPostModal() {
        console.log('showPostModal() called');
        
        if (!this.isAuthenticated || this.userType !== 'member') {
            alert('Only members can create posts');
            return;
        }

        const modal = document.getElementById('post-modal');
        if (modal) {
            console.log('Showing modal...');
            modal.style.display = 'flex';
            modal.classList.add('active');
            
            // Reset form
            const form = document.getElementById('post-form');
            if (form) form.reset();
            
            // Clear images
            this.selectedImages = [];
            this.updateImagePreview('post');
            this.updateCharCount();
            
            // Focus textarea
            setTimeout(() => {
                const textarea = document.getElementById('post-content');
                if (textarea) textarea.focus();
            }, 100);
        } else {
            console.error('Modal not found!');
        }
    }

    closeModals() {
        console.log('Closing modals...');
        const modals = document.querySelectorAll('.modal-overlay');
        modals.forEach(modal => {
            modal.style.display = 'none';
            modal.classList.remove('active');
        });
    }

    handleImageSelection(event, type = 'post') {
        console.log('handleImageSelection called for:', type);
        const files = Array.from(event.target.files);
        const maxSize = 2 * 1024 * 1024; // 2MB
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        
        // Clear the appropriate array
        if (type === 'post') {
            this.selectedImages = [];
        } else {
            this.selectedReplyImages = [];
        }
        
        files.forEach((file) => {
            console.log(`Processing ${type} file:`, file.name);
            
            if (!allowedTypes.includes(file.type)) {
                alert(`File ${file.name} is not a valid image type`);
                return;
            }
            
            if (file.size > maxSize) {
                alert(`File ${file.name} is too large (max 2MB)`);
                return;
            }
            
            if (type === 'post') {
                this.selectedImages.push(file);
            } else {
                this.selectedReplyImages.push(file);
            }
        });
        
        const selectedCount = type === 'post' ? this.selectedImages.length : this.selectedReplyImages.length;
        console.log(`Valid ${type} images selected:`, selectedCount);
        this.updateImagePreview(type);
    }

    updateImagePreview(type = 'post') {
        const previewContainer = document.getElementById(type === 'post' ? 'image-preview' : 'reply-image-preview');
        if (!previewContainer) {
            console.log(`Preview container for ${type} not found`);
            return;
        }
        
        const images = type === 'post' ? this.selectedImages : this.selectedReplyImages;
        console.log(`Updating ${type} image preview for ${images.length} images`);
        
        previewContainer.innerHTML = '';
        
        images.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const previewDiv = document.createElement('div');
                previewDiv.style.cssText = `
                    position: relative;
                    width: 80px;
                    height: 80px;
                    display: inline-block;
                    margin: 5px;
                `;
                
                previewDiv.innerHTML = `
                    <img src="${e.target.result}" style="
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                        border-radius: 8px;
                        border: 2px solid #e1e5e9;
                    ">
                    <button type="button" onclick="sharingManager.removeImage(${index}, '${type}')" style="
                        position: absolute;
                        top: -8px;
                        right: -8px;
                        background: #e74c3c;
                        color: white;
                        border: none;
                        border-radius: 50%;
                        width: 20px;
                        height: 20px;
                        font-size: 12px;
                        cursor: pointer;
                    ">×</button>
                `;
                previewContainer.appendChild(previewDiv);
            };
            reader.readAsDataURL(file);
        });
    }

    removeImage(index, type = 'post') {
        console.log(`Removing ${type} image at index:`, index);
        
        if (type === 'post') {
            this.selectedImages.splice(index, 1);
            const fileInput = document.getElementById('post-images');
            if (fileInput) fileInput.value = '';
        } else {
            this.selectedReplyImages.splice(index, 1);
            const fileInput = document.getElementById('reply-images');
            if (fileInput) fileInput.value = '';
        }
        
        this.updateImagePreview(type);
    }

    async submitPost(form) {
        console.log('submitPost called');
        
        const submitBtn = form.querySelector('#submit-post');
        const originalText = submitBtn.innerHTML;
        
        try {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Posting...';
            
            const formData = new FormData();
            const content = form.querySelector('#post-content').value;
            
            formData.append('content', content);
            formData.append('_token', window.csrfToken);
            
            this.selectedImages.forEach((file) => {
                formData.append('images[]', file);
            });
            
            const response = await fetch('/posts', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const responseText = await response.text();
            let data;
            
            try {
                data = JSON.parse(responseText);
            } catch (e) {
                throw new Error('Invalid JSON response from server');
            }
            
            if (data.success) {
                this.closeModals();
                this.showAlert('success', 'Post created successfully!');
                await this.loadPosts();
            } else {
                throw new Error(data.message || 'Failed to create post');
            }
            
        } catch (error) {
            console.error('Error submitting post:', error);
            this.showAlert('error', error.message || 'Error creating post');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    }

    async submitReply(form) {
        console.log('submitReply called');
        
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        try {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Replying...';
            
            const formData = new FormData();
            const content = form.querySelector('#reply-content').value;
            const parentId = form.querySelector('#reply-parent-id').value;
            
            if (!content.trim()) {
                throw new Error('Reply content is required');
            }
            
            console.log('Reply data:', { 
                content, 
                parentId, 
                images: this.selectedReplyImages.length 
            });
            
            formData.append('content', content);
            formData.append('parent_id', parentId);
            formData.append('_token', window.csrfToken);
            
            // Add reply images
            this.selectedReplyImages.forEach((file, index) => {
                formData.append('images[]', file);
                console.log(`Added reply image ${index}:`, file.name);
            });
            
            // Debug FormData for reply
            console.log('Reply FormData entries:');
            for (let [key, value] of formData.entries()) {
                console.log(key, ':', value instanceof File ? value.name : value);
            }
            
            const response = await fetch('/posts', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            console.log('Reply response status:', response.status);
            
            const responseText = await response.text();
            console.log('Reply raw response:', responseText);
            
            let data;
            try {
                data = JSON.parse(responseText);
            } catch (e) {
                console.error('Failed to parse reply JSON response:', e);
                throw new Error('Invalid JSON response from server');
            }
            
            console.log('Reply parsed response:', data);
            
            if (data.success) {
                console.log('Reply created successfully');
                this.closeModals();
                this.showAlert('success', 'Reply posted successfully!');
                
                // Clear reply images
                this.selectedReplyImages = [];
                
                await this.loadPosts();
            } else {
                throw new Error(data.message || 'Failed to create reply');
            }
            
        } catch (error) {
            console.error('Error submitting reply:', error);
            this.showAlert('error', error.message || 'Error creating reply');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    }

    async toggleLike(postId, actionItem) {
        console.log('toggleLike called for post:', postId);
        
        if (!this.isAuthenticated || this.userType !== 'member') {
            this.showAlert('error', 'Only members can like posts');
            return;
        }

        try {
            const response = await fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrfToken
                }
            });

            const data = await response.json();

            if (data.success) {
                const icon = actionItem.querySelector('i');
                const likesCount = actionItem.querySelector('.likes-count');
                
                if (data.is_liked) {
                    icon.className = 'fas fa-heart';
                    icon.style.color = '#e74c3c';
                } else {
                    icon.className = 'far fa-heart';
                    icon.style.color = '#666';
                }
                
                if (likesCount) {
                    likesCount.textContent = data.likes_count;
                }
            } else {
                this.showAlert('error', data.message || 'Error updating like');
            }
        } catch (error) {
            console.error('Error toggling like:', error);
            this.showAlert('error', 'Error updating like');
        }
    }

    async toggleBookmark(postId, actionItem) {
        console.log('toggleBookmark called for post:', postId);
        
        if (!this.isAuthenticated || this.userType !== 'member') {
            this.showAlert('error', 'Only members can bookmark posts');
            return;
        }

        try {
            const response = await fetch(`/posts/${postId}/bookmark`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrfToken
                }
            });

            const data = await response.json();

            if (data.success) {
                const icon = actionItem.querySelector('i');
                
                if (data.is_bookmarked) {
                    icon.className = 'fas fa-bookmark';
                    icon.style.color = '#f39c12';
                } else {
                    icon.className = 'far fa-bookmark';
                    icon.style.color = '#666';
                }
            } else {
                this.showAlert('error', data.message || 'Error updating bookmark');
            }
        } catch (error) {
            console.error('Error toggling bookmark:', error);
            this.showAlert('error', 'Error updating bookmark');
        }
    }

    confirmDeletePost(postId) {
        console.log('confirmDeletePost called for post:', postId);
        
        if (!this.isAuthenticated || this.userType !== 'member') {
            this.showAlert('error', 'Only members can delete posts');
            return;
        }

        // Find the post to check ownership
        const post = this.posts.find(p => p.id == postId);
        if (!post) {
            this.showAlert('error', 'Post not found');
            return;
        }

        // Check if user owns the post
        if (post.member_id != this.currentUser?.id) {
            this.showAlert('error', 'You can only delete your own posts');
            return;
        }

        // Show confirmation dialog
        const confirmed = confirm('Are you sure you want to delete this post? This action cannot be undone.');
        
        if (confirmed) {
            this.deletePost(postId);
        }
    }

    async deletePost(postId) {
        console.log('deletePost called for post:', postId);
        
        try {
            const response = await fetch(`/posts/${postId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrfToken
                }
            });

            const data = await response.json();

            if (data.success) {
                this.showAlert('success', 'Post deleted successfully');
                await this.loadPosts();
            } else {
                this.showAlert('error', data.message || 'Error deleting post');
            }
        } catch (error) {
            console.error('Error deleting post:', error);
            this.showAlert('error', 'Error deleting post');
        }
    }

    showReplyModal(postId) {
        console.log('showReplyModal called for post:', postId);
        
        if (!this.isAuthenticated || this.userType !== 'member') {
            this.showAlert('error', 'Only members can reply to posts');
            return;
        }

        const modal = document.getElementById('reply-modal');
        const parentIdInput = document.getElementById('reply-parent-id');
        const originalPostPreview = document.getElementById('original-post-preview');
        
        if (modal && parentIdInput) {
            parentIdInput.value = postId;
            
            // Find the original post
            const originalPost = this.posts.find(p => p.id == postId);
            
            // Show original post preview
            if (originalPostPreview && originalPost) {
                originalPostPreview.innerHTML = `
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 3px solid #667eea;">
                        <div style="font-size: 12px; color: #667eea; margin-bottom: 8px; font-weight: 500;">
                            <i class="fas fa-reply" style="transform: scaleX(-1);"></i> Replying to:
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                            <img src="${originalPost.member?.profile_image || '/image/user.png'}" 
                                 style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">
                            <span style="font-weight: 600; color: #333;">${originalPost.member?.username || 'Unknown User'}</span>
                        </div>
                        <p style="color: #666; font-size: 14px; line-height: 1.4; margin: 0;">
                            ${this.escapeHtml(originalPost.content).slice(0, 150)}${originalPost.content.length > 150 ? '...' : ''}
                        </p>
                    </div>
                `;
            }
            
            modal.style.display = 'flex';
            modal.classList.add('active');
            
            // Reset form
            const form = document.getElementById('reply-form');
            if (form) form.reset();
            
            // Clear reply images
            this.selectedReplyImages = [];
            this.updateImagePreview('reply');
            
            // Focus textarea
            setTimeout(() => {
                const textarea = document.getElementById('reply-content');
                if (textarea) textarea.focus();
            }, 100);
        }
    }

    async loadPosts() {
        console.log('Loading posts...');
        try {
            const response = await fetch('/api/posts', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                this.posts = data.posts;
                this.renderPosts();
            } else {
                throw new Error(data.message || 'Failed to load posts');
            }
        } catch (error) {
            console.error('Error loading posts:', error);
            this.showPostsError('Failed to load posts: ' + error.message);
        }
    }

    renderPosts() {
        console.log('Rendering posts:', this.posts?.length || 0);
        const container = document.getElementById('posts-container');
        if (!container) return;

        if (!this.posts || this.posts.length === 0) {
            container.innerHTML = `
                <div style="text-align: center; padding: 40px; background: white; border-radius: 15px; margin: 20px 0;">
                    <i class="fas fa-comments" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
                    <p>No posts yet. Be the first to share your experience!</p>
                </div>
            `;
            return;
        }

        const postsHTML = this.posts.map(post => this.createPostHTML(post)).join('');
        container.innerHTML = postsHTML;
    }

    createPostHTML(post) {
        const images = post.images && post.images.length > 0 
            ? `<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px; margin: 15px 0;">
                ${post.images.map(img => `<img src="/storage/${img}" alt="Post image" style="width: 100%; height: 150px; object-fit: cover; border-radius: 10px; cursor: pointer;">`).join('')}
               </div>`
            : '';

        // Check if this is a reply
        const isReply = post.parent_id !== null;
        const replyIndicator = isReply ? `
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px; color: #667eea; font-size: 12px; font-weight: 500;">
                <i class="fas fa-reply" style="transform: scaleX(-1);"></i>
                <span>Reply to ${post.parent?.member?.username || 'post'}</span>
            </div>
        ` : '';

        // Check if current user owns this post
        const canDelete = this.userType === 'member' && this.currentUser && post.member_id == this.currentUser.id;

        // Action buttons for members
        let actionsHTML = '';
        if (this.userType === 'member') {
            const likeClass = post.is_liked ? 'fas fa-heart' : 'far fa-heart';
            const likeColor = post.is_liked ? '#e74c3c' : '#666';
            const bookmarkClass = post.is_bookmarked ? 'fas fa-bookmark' : 'far fa-bookmark';
            const bookmarkColor = post.is_bookmarked ? '#f39c12' : '#666';
            
            // Delete button only for post owner
            const deleteButton = canDelete ? `
                <div class="action-item" data-action="delete" style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 8px 12px; border-radius: 6px; transition: background-color 0.3s ease; margin-left: auto;">
                    <i class="fas fa-trash" style="font-size: 16px; color: #e74c3c;" title="Delete post"></i>
                </div>
            ` : '';
            
            actionsHTML = `
                <div style="display: flex; gap: 20px; margin-top: 15px; padding-top: 15px; border-top: 1px solid #f0f0f0; align-items: center;">
                    <div class="action-item" data-action="like" style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 8px 12px; border-radius: 6px; transition: background-color 0.3s ease;">
                        <i class="${likeClass}" style="font-size: 16px; color: ${likeColor};"></i>
                        <span class="likes-count" style="color: #666; font-size: 13px;">${post.likes_count || 0}</span>
                    </div>
                    <div class="action-item" data-action="comment" style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 8px 12px; border-radius: 6px; transition: background-color 0.3s ease;">
                        <i class="fas fa-comment" style="font-size: 16px; color: #666;"></i>
                        <span class="replies-count" style="color: #666; font-size: 13px;">${post.replies_count || 0}</span>
                    </div>
                    <div class="action-item" data-action="bookmark" style="display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 8px 12px; border-radius: 6px; transition: background-color 0.3s ease;">
                        <i class="${bookmarkClass}" style="font-size: 16px; color: ${bookmarkColor};"></i>
                    </div>
                    ${deleteButton}
                </div>
            `;
        } else {
            actionsHTML = `
                <div style="display: flex; gap: 20px; margin-top: 15px; padding-top: 15px; border-top: 1px solid #f0f0f0; color: #666; font-size: 13px;">
                    <span><i class="fas fa-heart"></i> ${post.likes_count || 0} likes</span>
                    <span><i class="fas fa-comment"></i> ${post.replies_count || 0} replies</span>
                </div>
            `;
        }

        // Different styling for replies
        const postStyle = isReply ? `
            background: #f8f9fa; 
            margin: 10px 0 10px 40px; 
            padding: 20px; 
            border-radius: 10px; 
            border-left: 3px solid #667eea;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        ` : `
            background: white; 
            margin: 20px 0; 
            padding: 25px; 
            border-radius: 15px; 
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        `;

        return `
            <div class="post" data-post-id="${post.id}" style="${postStyle}">
                ${replyIndicator}
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 15px;">
                    <img src="${post.member?.profile_image ? '/storage/profile_images/' + post.member.profile_image : '/image/user.png'}" 
                         alt="Profile" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                    <div>
                        <div style="font-weight: 600; color: #333;">${post.member?.username || 'Unknown User'}</div>
                        <div style="color: #666; font-size: 12px;">${this.formatTimeAgo(post.created_at)}</div>
                    </div>
                </div>
                <div>
                    <p style="color: #333; line-height: 1.6; margin-bottom: 15px;">${this.escapeHtml(post.content)}</p>
                    ${images}
                    ${actionsHTML}
                </div>
            </div>
        `;
    }

    showPostsError(message) {
        const container = document.getElementById('posts-container');
        if (container) {
            container.innerHTML = `
                <div style="text-align: center; padding: 40px; color: #e74c3c; background: #fee; border-radius: 15px; margin: 20px 0;">
                    <p>${message}</p>
                    <button onclick="sharingManager.loadPosts()" style="background: #667eea; color: white; border: none; padding: 10px 20px; border-radius: 5px; margin-top: 15px; cursor: pointer;">
                        Try Again
                    </button>
                </div>
            `;
        }
    }

    updateCharCount() {
        const textarea = document.getElementById('post-content');
        const counter = document.getElementById('char-count');
        
        if (textarea && counter) {
            counter.textContent = textarea.value.length;
        }
    }

    formatTimeAgo(dateString) {
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

    escapeHtml(text) {
        const map = {
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    showAlert(type, message) {
        console.log(`Alert (${type}):`, message);
        
        const existingAlerts = document.querySelectorAll('.alert');
        existingAlerts.forEach(alert => alert.remove());

        const alert = document.createElement('div');
        alert.className = `alert alert-${type}`;
        alert.style.cssText = `
            position: fixed; top: 20px; right: 20px; padding: 15px 20px; border-radius: 8px; z-index: 10001;
            background: ${type === 'success' ? '#d4edda' : '#f8d7da'};
            color: ${type === 'success' ? '#155724' : '#721c24'};
            border: 1px solid ${type === 'success' ? '#c3e6cb' : '#f5c6cb'};
        `;
        alert.innerHTML = `${message} <button onclick="this.parentElement.remove()" style="background: none; border: none; float: right; cursor: pointer;">&times;</button>`;

        document.body.appendChild(alert);
        setTimeout(() => alert.remove(), 5000);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing SharingManager');
    window.sharingManager = new SharingManager();
});

console.log('Sharing.js loaded successfully');