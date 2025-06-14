<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Handle dashboard redirect based on user authentication status
     */
    public function index()
    {
        // Cek apakah user sudah login sebagai admin
        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        // Cek apakah user sudah login sebagai member
        if (Auth::guard('member')->check()) {
            return redirect()->route('user.dashboard');
        }
        
        // Jika belum login, redirect ke sign in dengan pesan
        return redirect()->route('sign_in')->with('message', 'Silakan login terlebih dahulu untuk mengakses dashboard.');
    }
}