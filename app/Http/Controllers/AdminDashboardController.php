<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web'); // Guard untuk admin
    }

    public function index()
    {
        // Ambil data user yang sedang login sebagai admin
        $user = Auth::guard('web')->user();
        
        // Kirim data user ke view dashboard_admin
        return view('dashboard_admin', compact('user'));
    }
}