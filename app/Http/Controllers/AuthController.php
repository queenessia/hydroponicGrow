<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\User;
use App\Models\Member;

class AuthController extends Controller
{
    // Show Sign Up Form
    public function showSignUpForm()
    {
        return view('auth.sign_up');
    }

    // Show Sign In Form  
    public function showSignInForm()
    {
        return view('auth.sign_in');
    }

    // Handle Registration
    public function register(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Cek apakah email sudah ada di tabel users atau members
        $emailExistsInUsers = User::where('email', $request->email)->exists();
        $emailExistsInMembers = Member::where('email', $request->email)->exists();
        
        // Check username in both tables
        $usernameExistsInUsers = User::where('username', $request->username)->exists();
        $usernameExistsInMembers = Member::where('username', $request->username)->exists();

        if ($emailExistsInUsers || $emailExistsInMembers) {
            return back()->withErrors(['email' => 'Email sudah terdaftar.']);
        }

        if ($usernameExistsInUsers || $usernameExistsInMembers) {
            return back()->withErrors(['username' => 'Username sudah digunakan.']);
        }

        // Logic pemisahan berdasarkan email
        if (str_starts_with($request->email, 'admin@')) {
            // Registrasi sebagai Admin (ke tabel users)
            User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'username' => $request->username, // Add this line
                'first_name' => $request->first_name, // Add this line
                'last_name' => $request->last_name, // Add this line
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
        } else {
            // Registrasi sebagai Member (ke tabel members)
            Member::create([
                'email' => $request->email,
                'username' => $request->username,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('sign_in')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // Handle Login - Check All Databases
public function login(Request $request)
{
    $request->validate([
        'username' => ['required', 'string'],
        'password' => ['required', 'string'],
    ]);

    $loginField = $request->username;
    $password = $request->password;
    $isEmail = filter_var($loginField, FILTER_VALIDATE_EMAIL);

    // Prepare credentials
    $credentials = $isEmail 
        ? ['email' => $loginField, 'password' => $password]
        : ['username' => $loginField, 'password' => $password];

    // Method 1: Try Admin first (users table)
    if (Auth::guard('web')->attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended(route('admin.dashboard'));
    }

    // Method 2: Try Member (members table)
    if (Auth::guard('member')->attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended(route('user.dashboard'));
    }

    // Login failed
    return back()->withErrors([
        'username' => 'Username/Email atau password salah.',
    ])->onlyInput('username');
}

    // Handle Logout
    public function logout(Request $request)
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        } elseif (Auth::guard('member')->check()) {
            Auth::guard('member')->logout();
        }

        $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Optional: Flash message
    return redirect()->route('sign_in')->with('success', 'Berhasil logout');
    }
}