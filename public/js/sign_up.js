// Fungsi untuk toggle password visibility
function togglePasswordVisibility(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    } else {
        passwordInput.type = "password";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    }
}

// Event listeners untuk toggle password dan repassword
document.getElementById('togglePassword').addEventListener('click', function () {
    togglePasswordVisibility('password', 'togglePassword');
});

document.getElementById('toggleRepassword').addEventListener('click', function () {
    togglePasswordVisibility('repassword', 'toggleRepassword');
});

// // Event listener untuk tombol Sign Up
// document.getElementById('signUpButton').addEventListener('click', function () {
//     const nama = document.getElementById('nama').value.trim();
//     const username = document.getElementById('username').value.trim();
//     const password = document.getElementById('password').value;
//     const repassword = document.getElementById('repassword').value;

//     if (!nama || !username || !password || !repassword) {
//         alert("Silakan isi semua data terlebih dahulu!");
//     } else if (password !== repassword) {
//         alert("Password dan Konfirmasi Password tidak cocok!");
//     } else {
//         // Redirect ke halaman sharing jika semua valid
//         window.location.href = "/sharing";
//     }
// });
