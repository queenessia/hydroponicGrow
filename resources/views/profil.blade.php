<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pengaturan Akun</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link rel="stylesheet" href="{{ asset('css/profil.css') }}">
</head>

<body onload="initializePage()">

  <!-- Top Navigation -->
  <nav class="transparent-nav">
    <button class="drawer-toggle" onclick="toggleDrawer()">☰</button>
    <div class="nav-links">
      <a href="#" class="active-nav">Home</a>
      <a href="#">Article</a>
      <a href="#">Video</a>
      <a href="#">Sharing</a>
      <a href="#">Dashboard</a>
    </div>
  </nav>

  <!-- Drawer -->
  <div id="drawer" class="drawer">
    <div class="menu" onclick="toggleSubMenu('profilSubmenu')">
      <i class="fas fa-user-circle"></i> Profil
    </div>
    <div id="profilSubmenu" class="submenu">
      <div onclick="showKelolaAkun()"><i class="fas fa-cog"></i> Kelola Akun</div>
      <div onclick="logout()"><i class="fas fa-sign-out-alt"></i> Keluar</div>
    </div>

    <div class="menu" onclick="showDashboard()">
      <i class="fas fa-chart-line"></i> Dashboard
    </div>

    <div class="menu" onclick="toggleSubMenu('adminSubmenu')">
      <i class="fas fa-user-shield"></i> Manage Posting
    </div>
    <div id="adminSubmenu" class="submenu">
      <div onclick="showPosting()"><i class="fas fa-plus"></i> Posts</div>
      <div onclick="showPostingLiked()"><i class="fas fa-heart"></i> Likes</div>
      <div onclick="showPostingReplied()"><i class="fas fa-reply"></i> Replies</div>
      <div onclick="showPostingBookmarked()"><i class="fas fa-bookmark"></i> Bookmarks</div>
      <div onclick="showPostingMedia()"><i class="fas fa-photo-video"></i> Media</div>
    </div>
  </div>

  <!-- Content -->
  <div class="content">
    <div class="settings-container">
      <h2>Pengaturan Akun</h2>
      <form>
        <label for="nama">Nama</label>
        <input type="text" id="nama" name="nama">

        <label for="email">Email</label>
        <input type="email" id="email" name="email">

        <label for="password">Password</label>
        <input type="password" id="password" name="password">

        <label for="foto">Foto Profil</label>
        <input type="file" id="foto" name="foto">

        <button type="submit">Simpan Perubahan</button>
      </form>
    </div>
  </div>

  <script src="script.js"></script>
</body>
</html>