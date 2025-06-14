<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PlantController extends Controller
{
    // Menampilkan semua tanaman
    public function index()
    {
        $plants = Plant::all();
        
        // Jika request AJAX/JSON
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $plants
            ]);
        }
        
        return view('plants.index', compact('plants'));
    }

    // Menampilkan form tambah tanaman
    public function create()
    {
        return view('plants.create');
    }

    // Menyimpan tanaman baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'suhu' => 'required|numeric|min:0|max:50',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cara_menanam' => 'required|string',
            'kebutuhan_lingkungan' => 'required|string',
            'waktu_panen' => 'required|string',
            'tips_perawatan' => 'required|string',
        ]);

        if ($validator->fails()) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $data = $request->all();

        // Handle upload gambar
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/plants', $imageName);
            $data['image'] = $imageName;
        }

        $plant = Plant::create($data);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tanaman berhasil ditambahkan',
                'data' => $plant
            ], 201);
        }

        return redirect()->route('plants.index')
                       ->with('success', 'Tanaman berhasil ditambahkan');
    }

    // Menampilkan detail tanaman
    public function show($id)
    {
        $plant = Plant::find($id);

        if (!$plant) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tanaman tidak ditemukan'
                ], 404);
            }
            
            abort(404);
        }

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $plant
            ]);
        }

        return view('plants.show', compact('plant'));
    }

    // Menampilkan form edit
    public function edit($id)
    {
        $plant = Plant::find($id);

        if (!$plant) {
            abort(404);
        }

        return view('plants.edit', compact('plant'));
    }

    // Update tanaman
    public function update(Request $request, $id)
    {
        $plant = Plant::find($id);

        if (!$plant) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tanaman tidak ditemukan'
                ], 404);
            }
            
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'suhu' => 'required|numeric|min:0|max:50',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cara_menanam' => 'required|string',
            'kebutuhan_lingkungan' => 'required|string',
            'waktu_panen' => 'required|string',
            'tips_perawatan' => 'required|string',
        ]);

        if ($validator->fails()) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $data = $request->all();

        // Handle upload gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($plant->image && Storage::exists('public/plants/' . $plant->image)) {
                Storage::delete('public/plants/' . $plant->image);
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/plants', $imageName);
            $data['image'] = $imageName;
        }

        $plant->update($data);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tanaman berhasil diupdate',
                'data' => $plant
            ]);
        }

        return redirect()->route('plants.index')
                       ->with('success', 'Tanaman berhasil diupdate');
    }

    // Hapus tanaman
    public function destroy($id)
    {
        $plant = Plant::find($id);

        if (!$plant) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tanaman tidak ditemukan'
                ], 404);
            }
            
            abort(404);
        }

        // Hapus gambar jika ada
        if ($plant->image && Storage::exists('public/plants/' . $plant->image)) {
            Storage::delete('public/plants/' . $plant->image);
        }

        $plant->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tanaman berhasil dihapus'
            ]);
        }

        return redirect()->route('plants.index')
                       ->with('success', 'Tanaman berhasil dihapus');
    }
}