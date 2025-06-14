<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Media post</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="mediaHistory.css">
</head>
<body>
    <div class="appbar">
        <div class="appbar-content">
          <button class="drawer-toggle" onclick="toggleDrawer()">
            <i class="fas fa-bars"></i>
          </button>
          <img src="logo.png" alt="Logo" class="logo" />
          <div class="app-title">HydroponicGrow</div>
          <div class="nav-links" style="margin-left: 60px; gap: 160px;">
            <a href="#">Home</a>
            <a href="#">Article</a>
            <a href="#">Video</a>
            <a href="#">Sharing</a>
            <a href="#">Dashboard</a>
          </div>
    </div>
  </div>

  <div id="drawer" class="drawer">
    <!-- New Profile Menu -->
    <div class="menu" onclick="toggleSubMenu('profilSubmenu')">
      <i class="fas fa-user-circle"></i> Profil
    </div>
    <div id="profilSubmenu" class="submenu">
      <div onclick="showKelolaAkun()"><i class="fas fa-cog"></i>Kelola Akun</div>
      <div><i class="fas fa-sign-out-alt"></i>Keluar</div>
    </div>
    
    <div class="menu active" onclick="showDashboard()">
      <i class="fas fa-chart-line"></i> Dashboard
    </div>
    
    <div class="menu" onclick="toggleSubMenu('adminSubmenu')">
      <i class="fas fa-user-shield"></i> Posting Manage
    </div>
    <div id="adminSubmenu" class="submenu">
        <div onclick="showPosting()"><i class="fas fa-plus"></i>Posts</div>
        <div onclick="showPostingLiked()"><i class="fas fa-plus"></i>Likes</div>
        <div onclick="showPostingReplied()"><i class="fas fa-plus"></i>Replies</div>
        <div onclick="showPostingBookmarked()"><i class="fas fa-plus"></i>Bookmarks</div>
        <div onclick="showPostingBookmarked()"><i class="fas fa-plus"></i>Media</div>
    </div>
  </div>

  <div class="content">
    <h1 style="padding: 16px; font-size: 24px; font-weight: bold; color: #3A7F0D;">The Media You Posts</h1>
    <div class="card">
      <div class="card-header">
        <div class="profile-pic">
          <i class="fas fa-user"></i>
        </div>
        <div class="user-info">
          <div class="username">User Pertama</div>
          <div class="user-handle">@userpertama</div>
        </div>
      </div>
      <h3>Judul Card 1</h3>
      <p>aku barusan liat video ini dan sangat membantu</p>
      <div class="card-media">
        <iframe width="100%" height="315" src="https://www.youtube.com/embed/4w5i5Rh1-5k" 
          frameborder="10"></iframe>
      </div>
      <div class="card-actions">
        <div class="action-button reply-button" onclick="toggleLike(this)">
          <i class="far fa-comment"></i>
          <span>12</span>
        </div>
        <div class="action-button like-button" onclick="toggleLike(this)">
          <i class="far fa-heart"></i>
          <span>24</span>
        </div>
        <div class="action-button bookmarks-button" onclick="toggleLike(this)">
          <i class="far fa-bookmark"></i>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header">
        <div class="profile-pic">
          <i class="fas fa-user"></i>
        </div>
        <div class="user-info">
          <div class="username">User Pertama</div>
          <div class="user-handle">@userpertama</div>
        </div>
      </div>
      <h3>Judul Card 2</h3>
      <p>Deskripsi singkat dari card kedua. Informasi bisa diatur sesuai kebutuhan.</p>
      <div class="card-media">
        <img src="selada.png" alt="Media Postingan" class="media-img" />
      </div>
      <div class="card-actions">
        <div class="action-button reply-button" onclick="toggleLike(this)">
          <i class="far fa-comment"></i>
          <span>8</span>
        </div>
        <div class="action-button like-button" onclick="toggleLike(this)">
          <i class="far fa-heart"></i>
          <span>15</span>
        </div>
        <div class="action-button bookmarks-button" onclick="toggleLike(this)">
          <i class="far fa-bookmark"></i>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header">
        <div class="profile-pic">
          <i class="fas fa-user"></i>
        </div>
        <div class="user-info">
          <div class="username">User Pertama</div>
          <div class="user-handle">@userpertama</div>
        </div>
      </div>
      <h3>Judul Card 3</h3>
      <p>aku baru mau mencoba menanam pake hidrponik</p>
      <div class="card-media">
        <img src="/image/image 46.png" alt="Media Postingan" class="media-img" />
      </div>
        <div class="card-actions">
        <div class="action-button reply-button" onclick="toggleLike(this)">
          <i class="far fa-comment"></i>
          <span>20</span>
        </div>
        <div class="action-button like-button"onclick="toggleLike(this)">
          <i class="far fa-heart"></i>
          <span>42</span>
        </div>
        <div class="action-button bookmarks-button" onclick="toggleLike(this)">
          <i class="far fa-bookmark"></i>
        </div>
      </div>
    </div>
  </div>

  <script src="mediaHistory.js"></script>
</body>
</html>