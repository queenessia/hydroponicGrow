<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArtikelController extends Controller
{
    // Menampilkan daftar artikel (untuk API/AJAX) - ADMIN ONLY
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $artikels = Artikel::orderBy('created_at', 'desc')->get();
            return response()->json([
                'success' => true,
                'data' => $artikels
            ]);
        }
        
        return view('dashboard.artikel.index');
    }

    // NEW METHOD: Public method untuk mendapatkan artikel - accessible by all users
    public function getPublicArticles(Request $request)
    {
        try {
            $artikels = Artikel::orderBy('created_at', 'desc')->get();
            return response()->json([
                'success' => true,
                'data' => $artikels
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat artikel'
            ], 500);
        }
    }

    // Menyimpan artikel baru
    public function store(Request $request)
    {
        $request->validate([
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'published_date' => 'required|date',
            'source' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'link' => 'required|url'
        ]);

        $data = $request->all();

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $data['thumbnail'] = $thumbnailPath;
        }

        $artikel = Artikel::create($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Artikel berhasil disimpan!',
                'data' => $artikel
            ]);
        }

        return redirect()->back()->with('success', 'Artikel berhasil disimpan!');
    }

    // Menampilkan form edit
    public function edit($id)
    {
        $artikel = Artikel::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $artikel
        ]);
    }

    // Update artikel
    public function update(Request $request, $id)
    {
        $artikel = Artikel::findOrFail($id);
        
        $request->validate([
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'published_date' => 'required|date',
            'source' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'link' => 'required|url'
        ]);

        $data = $request->all();

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($artikel->thumbnail) {
                Storage::disk('public')->delete($artikel->thumbnail);
            }
            
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $data['thumbnail'] = $thumbnailPath;
        }

        $artikel->update($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Artikel berhasil diupdate!',
                'data' => $artikel
            ]);
        }

        return redirect()->back()->with('success', 'Artikel berhasil diupdate!');
    }

    // Hapus artikel
    public function destroy($id)
    {
        $artikel = Artikel::findOrFail($id);
        
        // Delete thumbnail file
        if ($artikel->thumbnail) {
            Storage::disk('public')->delete($artikel->thumbnail);
        }
        
        $artikel->delete();

        return response()->json([
            'success' => true,
            'message' => 'Artikel berhasil dihapus!'
        ]);
    }
}