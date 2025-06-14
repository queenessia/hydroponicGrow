<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Import Storage facade

class DashboardController extends Controller
{
    /**
     * Upload profile photo
     */
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();
        
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image) {
                Storage::delete('public/profile_images/' . $user->profile_image);
            }
            
            $fileName = time() . '.' . $request->profile_image->extension();
            $request->profile_image->storeAs('public/profile_images', $fileName);
            
            $user->profile_image = $fileName;
            $user->save();
            
            return response()->json([
                'success' => true,
                'filename' => $fileName,
                'message' => 'Profile image uploaded successfully'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'No file uploaded'
        ]);
    }
}