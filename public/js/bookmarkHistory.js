function toggleDrawer() {
    const drawer = document.getElementById("drawer");
    drawer.classList.toggle("open");
  }
  
  function toggleLike(button) {
    const icon = button.querySelector('i');
    const count = button.querySelector('span');
    button.classList.toggle('liked');
  
    if (button.classList.contains('liked')) {
      icon.classList.remove('far');
      icon.classList.add('fas');
      count.textContent = parseInt(count.textContent) + 1;
    } else {
      icon.classList.remove('fas');
      icon.classList.add('far');
      count.textContent = parseInt(count.textContent) - 1;
    }
  }
  
  function toggleSubMenu(id) {
    const submenu = document.getElementById(id);
    submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
  }
  
  function showDashboard() {
    alert('Tampilkan Dashboard');
  }
  function showKelolaAkun() {
    alert('Tampilkan Pengaturan Akun');
  }
  function showPosting() {
    alert('Tampilkan Daftar Postingan');
  }
  function showPostingLiked() {
    alert('Tampilkan Postingan yang Disukai');
  }
  function showPostingReplied() {
    alert('Tampilkan Postingan yang Dibalas');
  }
  function showPostingBookmarked() {
    alert('Tampilkan Bookmark dan Media');
  }
  