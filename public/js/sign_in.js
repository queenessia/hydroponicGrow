// Toggle Password Visibility - Single implementation
document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordInput = document.getElementById('password');
    const icon = this;
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    }
});

// Back Button - Navigate to home page
document.getElementById('backButton').addEventListener('click', function () {
    window.location.href = '/'; // Navigate to home page based on your routes
});

// Auto refresh CSRF token jika halaman idle
let idleTimer;
let idleTime = 0;

// Reset idle timer
function resetIdleTimer() {
    clearTimeout(idleTimer);
    idleTime = 0;
    idleTimer = setTimeout(function() {
        // Refresh halaman jika idle lebih dari 10 menit
        if (idleTime >= 600000) { // 10 menit
            window.location.reload();
        }
    }, 60000); // Check setiap menit
}

// Event listeners untuk reset timer
document.addEventListener('mousemove', resetIdleTimer);
document.addEventListener('keypress', resetIdleTimer);
document.addEventListener('click', resetIdleTimer);
document.addEventListener('scroll', resetIdleTimer);

// Initialize timer
resetIdleTimer();

// Handle form submission dengan retry
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitButton = document.getElementById('signInButton');
    
    if (form && submitButton) {
        form.addEventListener('submit', function(e) {
            // Disable button untuk prevent double submit
            submitButton.textContent = 'Signing In...';
            submitButton.disabled = true;
            
            // Re-enable button setelah 5 detik jika masih di halaman yang sama
            setTimeout(function() {
                if (submitButton) {
                    submitButton.textContent = 'Sign In';
                    submitButton.disabled = false;
                }
            }, 5000);
        });
    }
});

// Additional validation (optional)
function validateForm() {
    const username = document.getElementById('username');
    const password = document.getElementById('password');
    
    if (!username.value.trim()) {
        alert('Please enter your username');
        username.focus();
        return false;
    }
    
    if (!password.value.trim()) {
        alert('Please enter your password');
        password.focus();
        return false;
    }
    
    return true;
}