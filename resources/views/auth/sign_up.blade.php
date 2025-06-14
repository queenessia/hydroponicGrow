<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - HydroponicGrow</title>
    
    <!-- Font dan CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Amita:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sign_up.css') }}">
</head>
<body>
    
    <!-- Logo dan Tulisan -->
    <div class="logo-section">
        <img src="{{ asset('image/logo.png') }}" alt="Logo">
        <h2 class="amita-bold" style="font-size: 36px; margin: 10px 0;">HydroponicGrow</h2>
    </div>
    
    <!-- Kotak Sign Up -->
    <div class="container">
        <div class="box">
            <p class="title">Sign Up</p>
            
            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            {{-- Form Sign Up --}}
            <form action="{{ route('register') }}" method="POST">
             @csrf
                
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Email" value="{{ old('email') }}" required>
                </div>
                
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" placeholder="Username" value="{{ old('username') }}" required>
                </div>
                
                <div class="input-group">
                    <label for="namadepan">Nama Depan</label>
                    <input type="text" name="first_name" id="namadepan" placeholder="Nama Depan" value="{{ old('first_name') }}" required>
                </div>
                
                <div class="input-group">
                    <label for="namabelakang">Nama Belakang</label>
                    <input type="text" name="last_name" id="namabelakang" placeholder="Nama Belakang" value="{{ old('last_name') }}" required>
                </div>
                
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <i class="fas fa-eye-slash password-toggle" id="togglePassword"></i>
                </div>
                
                <div class="input-group">
                    <label for="repassword">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="repassword" placeholder="Konfirmasi Password" required>
                    <i class="fas fa-eye-slash password-toggle" id="toggleRepassword"></i>
                </div>
                
                <button type="submit" class="signup-button">Sign Up</button>
            </form>
            
            <p class="link">
                Sudah punya akun? 
                <a href="{{ route('sign_in') }}" style="color: #2A4E17; text-decoration: underline;">Sign In</a>
            </p>
        </div>
    </div>
    
    <script src="{{ asset('js/sign_up.js') }}"></script>
</body>
</html>
