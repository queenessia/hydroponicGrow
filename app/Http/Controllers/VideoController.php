<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $videos = Video::latest()->get();
            
            return response()->json([
                'success' => true,
                'data' => $videos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading videos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'source' => 'required|string|max:255',
            'published_date' => 'required|date',
            'link' => [
                'required',
                'string',
                'max:500', // Increased max length for YouTube URLs
                function ($attribute, $value, $fail) {
                    // Custom validation for YouTube URLs and other video platforms
                    $allowedDomains = [
                        'youtube.com',
                        'youtu.be',
                        'www.youtube.com',
                        'm.youtube.com',
                        'vimeo.com',
                        'www.vimeo.com',
                        'dailymotion.com',
                        'www.dailymotion.com'
                    ];
                    
                    // Parse the URL
                    $parsedUrl = parse_url($value);
                    
                    if (!$parsedUrl || !isset($parsedUrl['host'])) {
                        $fail('Format URL tidak valid.');
                        return;
                    }
                    
                    $host = strtolower($parsedUrl['host']);
                    $isValidDomain = false;
                    
                    foreach ($allowedDomains as $domain) {
                        if ($host === $domain || str_ends_with($host, '.' . $domain)) {
                            $isValidDomain = true;
                            break;
                        }
                    }
                    
                    if (!$isValidDomain) {
                        $fail('URL harus dari platform video yang didukung (YouTube, Vimeo, Dailymotion).');
                    }
                    
                    // Additional check for valid URL format
                    if (!filter_var($value, FILTER_VALIDATE_URL)) {
                        $fail('Format URL tidak valid.');
                    }
                }
            ]
        ], [
            'title.required' => 'Judul video wajib diisi.',
            'title.max' => 'Judul video maksimal 255 karakter.',
            'source.required' => 'Sumber video wajib diisi.',
            'source.max' => 'Sumber video maksimal 255 karakter.',
            'published_date.required' => 'Tanggal publikasi wajib diisi.',
            'published_date.date' => 'Format tanggal publikasi tidak valid.',
            'link.required' => 'Link video wajib diisi.',
            'link.max' => 'Link video maksimal 500 karakter.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $video = Video::create([
                'title' => $request->title,
                'source' => $request->source,
                'published_date' => $request->published_date,
                'link' => $request->link
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Video berhasil ditambahkan!',
                'data' => $video
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving video: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Video $video)
    {
        return response()->json([
            'success' => true,
            'data' => $video
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Video $video)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $video
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading video: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Video $video)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'source' => 'required|string|max:255',
            'published_date' => 'required|date',
            'link' => [
                'required',
                'string',
                'max:500',
                function ($attribute, $value, $fail) {
                    // Same custom validation as in store method
                    $allowedDomains = [
                        'youtube.com',
                        'youtu.be',
                        'www.youtube.com',
                        'm.youtube.com',
                        'vimeo.com',
                        'www.vimeo.com',
                        'dailymotion.com',
                        'www.dailymotion.com'
                    ];
                    
                    $parsedUrl = parse_url($value);
                    
                    if (!$parsedUrl || !isset($parsedUrl['host'])) {
                        $fail('Format URL tidak valid.');
                        return;
                    }
                    
                    $host = strtolower($parsedUrl['host']);
                    $isValidDomain = false;
                    
                    foreach ($allowedDomains as $domain) {
                        if ($host === $domain || str_ends_with($host, '.' . $domain)) {
                            $isValidDomain = true;
                            break;
                        }
                    }
                    
                    if (!$isValidDomain) {
                        $fail('URL harus dari platform video yang didukung (YouTube, Vimeo, Dailymotion).');
                    }
                    
                    if (!filter_var($value, FILTER_VALIDATE_URL)) {
                        $fail('Format URL tidak valid.');
                    }
                }
            ]
        ], [
            'title.required' => 'Judul video wajib diisi.',
            'title.max' => 'Judul video maksimal 255 karakter.',
            'source.required' => 'Sumber video wajib diisi.',
            'source.max' => 'Sumber video maksimal 255 karakter.',
            'published_date.required' => 'Tanggal publikasi wajib diisi.',
            'published_date.date' => 'Format tanggal publikasi tidak valid.',
            'link.required' => 'Link video wajib diisi.',
            'link.max' => 'Link video maksimal 500 karakter.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $video->update([
                'title' => $request->title,
                'source' => $request->source,
                'published_date' => $request->published_date,
                'link' => $request->link
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Video berhasil diupdate!',
                'data' => $video
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating video: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        try {
            $video->delete();

            return response()->json([
                'success' => true,
                'message' => 'Video berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting video: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get public videos for frontend display
     */
    public function getPublicVideos()
    {
        try {
            $videos = Video::orderBy('published_date', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $videos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching videos'
            ], 500);
        }
    }

    /**
     * Helper method to extract video ID from YouTube URL
     */
    private function extractYouTubeId($url)
    {
        $videoId = null;
        
        // Different YouTube URL formats
        $patterns = [
            '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/',
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/',
            '/youtu\.be\/([a-zA-Z0-9_-]+)/',
            '/youtube\.com\/v\/([a-zA-Z0-9_-]+)/'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                $videoId = $matches[1];
                break;
            }
        }
        
        return $videoId;
    }

    /**
     * Validate if YouTube video exists (optional method)
     */
    private function validateYouTubeVideo($url)
    {
        $videoId = $this->extractYouTubeId($url);
        
        if (!$videoId) {
            return false;
        }
        
        // You can add YouTube API validation here if needed
        // For now, just return true if we can extract the video ID
        return true;
    }
}