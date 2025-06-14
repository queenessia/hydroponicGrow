// // Define dashboard content as a constant
// const dashboardContent = `
//   <h1 class="content-title">General Information</h1>
  
//   <!-- Container untuk box 1-3 -->
//   <div class="box-container">
//     <!-- Box 1 -->
//     <div class="box">
//       <img src="/image/artikelAdmin.png" alt="Icon 1" class="box-icon">
//       <h3>Total Artikel</h3>
//       <div class="big-zero">100</div>
//     </div>
    
//     <!-- Box 2 -->
//     <div class="box">
//       <img src="/image/videoAdmin.png" alt="Icon 2" class="box-icon">
//       <h3>Total Video</h3>
//       <div class="big-zero">100</div>
//     </div>
    
//     <!-- Box 3 -->
//     <div class="box">
//       <img src="/Image/tanamanAdmin.png" alt="Icon 3" class="box-icon">
//       <h3>Total Tanaman Panduan Budidaya</h3>
//       <div class="big-zero">0</div>
//     </div>
//   </div>
  
//   <!-- Box 4 -->
//   <div class="box-wide">
//     <img src="/image/kunjunganWebsite.png" alt="Icon 4" class="box-icon">
//     <h3>Kunjungan Website</h3>
//     <div class="wide-zero">1,234</div>
//   </div>
  
//   <!-- Box 5 -->
//   <div class="box-wide">
//     <img src="/image/userSignIn.png" alt="Icon 5" class="box-icon">
//     <h3>User Sign In</h3>
//     <div class="wide-zero">567</div>
//   </div>
  
//   <!-- Box 6 -->
//   <div class="box-wide">
//     <img src="/image/kelolaPostingan.png" alt="Icon 6" class="box-icon">
//     <h3>Kelola Postingan</h3>
//     <div class="wide-zero"></div>
//   </div>`;

// // Define add article form content
// const addArticleContent = `
//   <h1 class="content-title">Tambah Artikel Baru</h1>
  
//   <div class="form-container">
//     <form id="articleForm">
//       <!-- Thumbnail Upload -->
//       <div class="form-group">
//         <label for="thumbnail">Thumbnail Artikel</label>
//         <input type="file" id="thumbnail" class="form-control" accept="image/*">
//         <img id="thumbnailPreview" class="thumbnail-preview" alt="Thumbnail Preview">
//       </div>
      
//       <!-- Published Date -->
//       <div class="form-group">
//         <label for="publishedDate">Tanggal Publikasi</label>
//         <input type="date" id="publishedDate" class="form-control" required>
//       </div>
      
//       <!-- Source -->
//       <div class="form-group">
//         <label for="source">Sumber Artikel</label>
//         <input type="text" id="source" class="form-control" placeholder="Masukkan sumber artikel" required>
//       </div>
      
//       <!-- Title -->
//       <div class="form-group">
//         <label for="title">Judul Artikel</label>
//         <input type="text" id="title" class="form-control" placeholder="Masukkan judul artikel" required>
//       </div>
      
//       <!-- Description -->
//       <div class="form-group">
//         <label for="description">Deskripsi Artikel</label>
//         <textarea id="description" class="form-control" placeholder="Masukkan deskripsi artikel" required></textarea>
//       </div>
      
//       <!-- Link -->
//       <div class="form-group">
//         <label for="link">Link Artikel</label>
//         <input type="url" id="link" class="form-control" placeholder="https://example.com" required>
//       </div>
      
//       <!-- Buttons -->
//       <div class="form-group">
//         <button type="button" class="btn-cancel" id="cancelArticle">Batal</button>
//         <button type="submit" class="btn-submit">Simpan Artikel</button>
//       </div>
//     </form>
//   </div>`;

// // Define add video form content
// const addVideoContent = `
//   <h1 class="content-title">Tambah Video Baru</h1>
  
//   <div class="form-container">
//     <form id="videoForm">
//       <!-- Thumbnail Upload -->
//       <div class="form-group">
//         <label for="thumbnail">Thumbnail Video</label>
//         <input type="file" id="thumbnail" class="form-control" accept="image/*">
//         <img id="thumbnailPreview" class="thumbnail-preview" alt="Thumbnail Preview">
//       </div>
      
//       <!-- Judul -->
//       <div class="form-group">
//         <label for="judul">Judul Video</label>
//         <input type="text" id="judul" class="form-control" placeholder="Masukkan Judul Video" required>
//       </div>
      
//       <!-- Source -->
//       <div class="form-group">
//         <label for="source">Sumber Video</label>
//         <input type="text" id="source" class="form-control" placeholder="Masukkan sumber video" required>
//       </div>
      
//       <!-- Like -->
//       <div class="form-group">
//         <label for="like">Total Like</label>
//         <input type="text" id="like" class="form-control" placeholder="Masukkan jumlah like" required>
//       </div>
      
//       <!-- Link -->
//       <div class="form-group">
//         <label for="link">Link Video</label>
//         <input type="url" id="link" class="form-control" placeholder="https://example.com" required>
//       </div>
      
//       <!-- Buttons -->
//       <div class="form-group">
//         <button type="button" class="btn-cancel" id="cancelVideo">Batal</button>
//         <button type="submit" class="btn-submit">Simpan Video</button>
//       </div>
//     </form>
//   </div>`;

// // Define add tanaman form content
// const addTanamanContent = `
//   <h1 class="content-title">Tambah Tanaman Baru</h1>
  
//   <div class="form-container">
//     <form id="tanamanForm">
//       <!-- Thumbnail Upload -->
//       <div class="form-group">
//         <label for="thumbnail">Thumbnail Tanaman</label>
//         <input type="file" id="thumbnail" class="form-control" accept="image/*">
//         <img id="thumbnailPreview" class="thumbnail-preview" alt="Thumbnail Preview">
//       </div>
      
//       <!-- Name -->
//       <div class="form-group">
//         <label for="nama">Nama Tanaman</label>
//         <input type="text" id="nama" class="form-control" placeholder="Masukkan nama tanaman" required>
//       </div>
      
//       <!-- Suhu -->
//       <div class="form-group">
//         <label for="suhu">Suhu</label>
//         <input type="text" id="suhu" class="form-control" placeholder="Masukkan suhu" required>
//       </div>
      
//       <!-- Description -->
//       <div class="form-group">
//         <label for="description">Deskripsi tanaman</label>
//         <textarea id="description" class="form-control" placeholder="Masukkan deskripsi tanaman" required></textarea>
//       </div>
      
//       <!-- Buttons -->
//       <div class="form-group">
//         <button type="button" class="btn-cancel" id="cancelTanaman">Batal</button>
//         <button type="submit" class="btn-submit">Simpan Tanaman</button>
//       </div>
//     </form>
//   </div>`;

// // Initialize the page
// function initializePage() {
//   setupEventListeners();
//   showDashboard();
// }

// // Setup all event listeners
// function setupEventListeners() {
//   // Drawer toggle
//   document.querySelector('.drawer-toggle').addEventListener('click', toggleDrawer);
  
//   // Menu items with submenus
//   document.querySelectorAll('[data-submenu]').forEach(menu => {
//     menu.addEventListener('click', function() {
//       toggleSubMenu(this.getAttribute('data-submenu'));
//     });
//   });
  
//   // Menu items with actions
//   document.querySelectorAll('[data-action]').forEach(item => {
//     item.addEventListener('click', function() {
//       const action = this.getAttribute('data-action');
//       if (typeof window[action] === 'function') {
//         window[action]();
//       }
//     });
//   });
  
//   // Navbar links
//   document.querySelectorAll('.nav-links a').forEach(link => {
//     link.addEventListener('click', function(e) {
//       if (this.getAttribute('href') === '#') {
//         e.preventDefault();
//         showDashboard();
//       }
//     });
//   });
// }

// // Toggle drawer function
// function toggleDrawer() {
//   const drawer = document.getElementById("drawer");
//   const content = document.getElementById("mainContent");
//   drawer.classList.toggle("open");
//   content.classList.toggle("drawer-open");
// }

// // Toggle submenu function
// function toggleSubMenu(id) {
//   const submenu = document.getElementById(id);
//   submenu.classList.toggle('active');
// }

// // Update active navigation
// function updateActiveNav(menuIndex) {
//   // Update active state in sidebar
//   const menus = document.querySelectorAll('.menu');
//   menus.forEach((menu, index) => {
//     if (index === menuIndex) {
//       menu.classList.add('active');
//     } else {
//       menu.classList.remove('active');
//     }
//   });
  
//   // Update active state in top navigation
//   const navLinks = document.querySelectorAll('.nav-links a');
//   navLinks.forEach((link, index) => {
//     if (index === menuIndex) {
//       link.classList.add('active-nav');
//     } else {
//       link.classList.remove('active-nav');
//     }
//   });
// }

// // Preview thumbnail function
// function previewThumbnail(event) {
//   const input = event.target;
//   const preview = document.getElementById('thumbnailPreview');
  
//   if (input.files && input.files[0]) {
//     const reader = new FileReader();
    
//     reader.onload = function(e) {
//       preview.src = e.target.result;
//       preview.style.display = 'block';
//     }
    
//     reader.readAsDataURL(input.files[0]);
//   }
// }

// // Cancel form function
// function cancelForm() {
//   if (confirm('Apakah Anda yakin ingin membatalkan? Perubahan yang belum disimpan akan hilang.')) {
//     showArtikelTable();
//   }
// }

// // Show dashboard function
// function showDashboard() {
//   updateActiveNav(4); // Dashboard is the fifth menu item (index 4)
  
//   // Clear active submenu if any
//   const submenuItems = document.querySelectorAll('.submenu div');
//   submenuItems.forEach(item => item.classList.remove('active-item'));
  
//   // Update content with dashboard
//   document.getElementById("mainContent").innerHTML = dashboardContent;
// }

// // Show account management
// function showKelolaAkun() {
//   // Set profile menu as active
//   updateActiveNav(0);
  
//   document.getElementById("mainContent").innerHTML = `
//     <h1 class="content-title">Kelola Akun</h1>
    
//     <!-- Profil User -->
//     <div class="user-profile">
//         <img src="user.png" alt="User Photo" class="user-image">
//         <div class="user-name">Nama Depan Nama Belakang</div>
//     </div>

//     <!-- Upload New Photo Button -->
//     <button class="upload-photo-btn">Upload New Photo</button>
    
//     <!-- Box informasi user -->
//     <div class="user-info-container">
//         <div class="info-label">Username</div>
//         <div class="info-box">username</div>
        
//         <div class="info-label">Email</div>
//         <div class="info-box">user@email.com</div>
        
//         <div class="info-label">Role</div>
//         <div class="info-box">Admin</div>
        
//         <div class="info-label">Tanggal Bergabung</div>
//         <div class="info-box">1 Januari 2024</div>
//     </div>
    
//     <style>
//         .user-profile {
//             position: relative;
//             z-index: 2;
//             display: flex;
//             flex-direction: column;
//             align-items: center;
//             margin-top: 40px;
//             width: 100%;
//         }
        
//         .user-image {
//             width: 120px;
//             height: 120px;
//             border-radius: 50%;
//             object-fit: cover;
//             border: 3px solid white;
//             margin-bottom: 15px;
//         }
        
//         .user-name {
//             color: #396929;
//             font-size: 18px;
//             font-weight: bold;
//             text-align: center;
//             margin-top: 10px;
//         }

//         /* Upload Photo Button */
//         .upload-photo-btn {
//             position: relative;
//             background-color: #F8F6DF;
//             color: #333;
//             padding: 8px 15px;
//             border-radius: 30px;
//             border: none;
//             font-size: 14px;
//             font-weight: bold;
//             cursor: pointer;
//             margin: 20px auto;
//             box-shadow: 0 2px 4px rgba(0,0,0,0.1);
//             transition: all 0.3s ease;
//             width: auto;
//             display: block;
//         }

//         .upload-photo-btn:hover {
//             background-color: #e8e6cf;
//             transform: translateY(-2px);
//         }
        
//         /* Style untuk box informasi user */
//         .user-info-container {
//             position: relative;
//             width: 100%;
//             max-width: 500px;
//             margin: 30px auto;
//             padding: 0 10px;
//         }
        
//         .info-label {
//             font-size: 14px;
//             font-weight: bold;
//             margin-bottom: 5px;
//             color: #396929;
//         }
        
//         .info-box {
//             background-color: white;
//             border: 1px solid #396929;
//             border-radius: 8px;
//             padding: 10px;
//             margin-bottom: 15px;
//             width: 100%;
//             box-shadow: 0 2px 4px rgba(0,0,0,0.1);
//         }
//     </style>`;
// }

// // Logout function
// function logout() {
//   alert('Anda akan keluar dari sistem');
//   // Redirect to login page
//   window.location.href = 'login.html';
// }

// // Show article table
// function showArtikelTable() {
//   // Set admin menu as active
//   updateActiveNav(3);
//   // Set article submenu as active
//   const submenuItems = document.querySelectorAll('#adminSubmenu div');
//   submenuItems.forEach(item => item.classList.remove('active-item'));
//   submenuItems[0].classList.add('active-item');
  
//   document.getElementById("mainContent").innerHTML = `
//     <h1 class="content-title">Kelola Artikel</h1>
//     <button id="btn-add1" class="btn-add">+ Tambah Artikel</button>
//     <table>
//       <thead>
//         <tr>
//           <th>No</th>
//           <th>Thumbnail</th>
//           <th>Published Date</th>
//           <th>Source</th>
//           <th>Title</th>
//           <th>Description</th>
//           <th>Link</th>
//           <th>Aksi</th>
//         </tr>
//       </thead>
//       <tbody>
//         <tr>
//           <td>1</td>
//           <td class="thumbnail-container"><img src="/image/article1.jpg" class="article-image" /></td>
//           <td>9 Maret 2025</td>
//           <td>Lorem ipsum</td>
//           <td>Lorem ipsum dolor sit amet</td>
//           <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam pharetra blandit convallis. </td>
//           <th><a href="http://www.example.com" target="_blank">Link</a></th>
//           <td><button class='btn-edit'>Edit</button> <button class='btn-delete'>Delete</button></td>
//         </tr>
//         <tr>
//           <td>2</td>
//           <td class="thumbnail-container"><img src="/image/article1.jpg" class="article-image" /></td>
//           <td>9 Maret 2025</td>
//           <td>Lorem ipsum</td>
//           <td>Lorem ipsum dolor sit amet</td>
//           <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam pharetra blandit convallis. </td>
//           <th><a href="http://www.example.com" target="_blank">Link</a></th>
//           <td><button class='btn-edit'>Edit</button> <button class='btn-delete'>Delete</button></td>
//         </tr>
//       </tbody>
//     </table>`;
    
//   // Add event listener for the add button
//   document.getElementById("btn-add1").addEventListener("click", showAddArticleForm);
// }

// // Show add article form
// function showAddArticleForm() {
//   // Set admin menu as active
//   updateActiveNav(3);
//   // Set article submenu as active
//   const submenuItems = document.querySelectorAll('#adminSubmenu div');
//   submenuItems.forEach(item => item.classList.remove('active-item'));
//   submenuItems[0].classList.add('active-item');
  
//   // Show add article form
//   document.getElementById("mainContent").innerHTML = addArticleContent;
  
//   // Set today's date as default
//   document.getElementById('publishedDate').value = new Date().toISOString().split('T')[0];
  
//   // Setup thumbnail preview
//   document.getElementById('thumbnail').addEventListener('change', previewThumbnail);
  
//   // Setup cancel button
//   document.getElementById('cancelArticle').addEventListener('click', showArtikelTable);
  
//   // Form submission handler
//   document.getElementById('articleForm').addEventListener('submit', function(e) {
//     e.preventDefault();
    
//     // Simulate data saving
//     alert('Artikel berhasil disimpan!');
    
//     // Return to article table
//     showArtikelTable();
//   });
// }

// // Show video table
// function showVideoTable() {
//   // Set admin menu as active
//   updateActiveNav(3);
//   // Set video submenu as active
//   const submenuItems = document.querySelectorAll('#adminSubmenu div');
//   submenuItems.forEach(item => item.classList.remove('active-item'));
//   submenuItems[1].classList.add('active-item');
  
//   document.getElementById("mainContent").innerHTML = `
//     <h1 class="content-title">Kelola Video</h1>
//     <button id="btn-add2" class="btn-add">+ Tambah Video</button>
//     <table>
//       <thead>
//         <tr>
//           <th>No</th>
//           <th>Thumbnail</th>
//           <th>Judul</th>
//           <th>Sumber</th>
//           <th>Like</th>
//           <th>Link</th>
//           <th>Aksi</th>
//         </tr>
//       </thead>
//       <tbody>
//         <tr>
//           <td>1</td>
//           <td class="thumbnail-container"><img src="/image/video.png" class="article-image" /></td>
//           <td>Lorem ipsum dolor sit amet</td>
//           <td>lorem ipsum</td>
//           <td>100</td>
//           <th><a href="http://www.youtube.com" target="_blank">Link</a></th>
//           <td><button class='btn-edit'>Edit</button> <button class='btn-delete'>Delete</button></td>
//         </tr>
//         <tr>
//           <td>2</td>
//           <td class="thumbnail-container"><img src="/image/video.png" class="article-image" /></td>
//           <td>Lorem ipsum dolor sit amet</td>
//           <td>lorem ipsum</td>
//           <td>100</td>
//           <th><a href="http://www.youtube.com" target="_blank">Link</a></th>
//           <td><button class='btn-edit'>Edit</button> <button class='btn-delete'>Delete</button></td>
//         </tr>
//       </tbody>
//     </table>`;
    
//   // Add event listener for the add button
//   document.getElementById("btn-add2").addEventListener("click", showAddVideoForm);
// }

// // Show add video form
// function showAddVideoForm() {
//   // Set admin menu as active
//   updateActiveNav(3);
//   // Set video submenu as active
//   const submenuItems = document.querySelectorAll('#adminSubmenu div');
//   submenuItems.forEach(item => item.classList.remove('active-item'));
//   submenuItems[1].classList.add('active-item');
  
//   // Show add video form
//   document.getElementById("mainContent").innerHTML = addVideoContent;
  
//   // Setup thumbnail preview
//   document.getElementById('thumbnail').addEventListener('change', previewThumbnail);
  
//   // Setup cancel button
//   document.getElementById('cancelVideo').addEventListener('click', showVideoTable);
  
//   // Form submission handler
//   document.getElementById('videoForm').addEventListener('submit', function(e) {
//     e.preventDefault();
    
//     // Simulate data saving
//     alert('Video berhasil disimpan!');
    
//     // Return to video table
//     showVideoTable();
//   });
// }

// // Show tanaman table
// function showTanamanTable() {
//   // Set admin menu as active
//   updateActiveNav(3);
//   // Set tanaman submenu as active
//   const submenuItems = document.querySelectorAll('#adminSubmenu div');
//   submenuItems.forEach(item => item.classList.remove('active-item'));
//   submenuItems[2].classList.add('active-item');
  
//   document.getElementById("mainContent").innerHTML = `
//     <h1 class="content-title">Kelola Tanaman</h1>
//     <button id="btn-add3" class="btn-add">+ Tambah Tanaman</button>
//     <table>
//       <thead>
//         <tr>
//           <th>No</th>
//           <th>Gambar</th>
//           <th>Nama Tanaman</th>
//           <th>Suhu</th>
//           <th>Description</th>
//           <th>Aksi</th>
//         </tr>
//       </thead>
//       <tbody>
//         <tr>
//           <td>1</td>
//           <td class="thumbnail-container"><img src="/image/kangkung.png" class="article-image" /></td>
//           <td>Kangkung</td>
//           <td>20</td>
//           <td>Lorem ipsum dolor sit amet</td>
//           <td><button class='btn-edit'>Edit</button> <button class='btn-delete'>Delete</button></td>
//         </tr>
//         <tr>
//           <td>2</td>
//           <td class="thumbnail-container"><img src="/image/pakcoy.png" class="article-image" /></td>
//           <td>Pakcoy</td>
//           <td>20</td>
//           <td>Lorem ipsum dolor sit amet</td>
//           <td><button class='btn-edit'>Edit</button> <button class='btn-delete'>Delete</button></td>
//         </tr>
//         <tr>
//           <td>3</td>
//           <td class="thumbnail-container"><img src="/image/daun_bawang.png" class="article-image" /></td>
//           <td>Daun Bawang</td>
//           <td>20</td>
//           <td>Lorem ipsum dolor sit amet</td>
//           <td><button class='btn-edit'>Edit</button> <button class='btn-delete'>Delete</button></td>
//         </tr>
//         <tr>
//           <td>4</td>
//           <td class="thumbnail-container"><img src="/image/selada.png" class="article-image" /></td>
//           <td>Selada</td>
//           <td>20</td>
//           <td>Lorem ipsum dolor sit amet</td>
//           <td><button class='btn-edit'>Edit</button> <button class='btn-delete'>Delete</button></td>
//         </tr>
//         <tr>
//           <td>5</td>
//           <td class="thumbnail-container"><img src="/image/bayam.png" class="article-image" /></td>
//           <td>Bayam</td>
//           <td>20</td>
//           <td>Lorem ipsum dolor sit amet</td>
//           <td><button class='btn-edit'>Edit</button> <button class='btn-delete'>Delete</button></td>
//         </tr>
//       </div>`};