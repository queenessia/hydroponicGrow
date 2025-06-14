<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sign In - HydroponicGrow</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Amita:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/sign_in.css') }}" />
</head>
<body>
    
    <!-- Tombol Back -->
    <div class="back-button" id="backButton">
        <i class="fas fa-arrow-left"></i>
    </div>
    
    <!-- Logo dan Tulisan HydroponicGrow -->
    <div class="logo-section">
        <img src="{{ asset('image/logo.png') }}" alt="Logo" />
        <h2 class="amita-bold" style="font-size: 36px; margin: 10px 0;">HydroponicGrow</h2>
    </div>
    
    <!-- Kotak Sign In -->
    <div class="container">
        <div class="box">
            <p class="title">Sign In</p>
            
            {{-- Success/Error Messages --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
           <form action="{{ route('login') }}" method="POST">
            @csrf
                
                <div class="input-group">
                    <label for="username">Username/Email</label>
                    <input type="text" id="username" name="username" placeholder="Username atau Email" value="{{ old('username') }}" required />
                </div>
                
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" required />
                    <i class="fas fa-eye-slash password-toggle" id="togglePassword"></i>
                </div>
                
                <button type="submit" class="signup-button" id="signInButton">Sign In</button>
            </form>
            
            <p class="link">Forget Password?</p>
            <p class="link">
                Belum punya akun?
                <a href="{{ route('sign_up') }}" style="color: #2A4E17; text-decoration: underline;">Sign Up</a>
            </p>
        </div>
    </div>
    
    <script src="{{ asset('js/sign_in.js') }}"></script>
</body>
</html>