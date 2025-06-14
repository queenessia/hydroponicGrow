<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Plant extends Model
{
    protected $fillable = [
        'name', 
        'suhu', 
        'description', 
        'image', 
        'cara_menanam', 
        'kebutuhan_lingkungan', 
        'waktu_panen', 
        'tips_perawatan'
    ];

    // Accessor untuk mendapatkan URL gambar
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::url('plants/' . $this->image);
        }
        return null;
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
    }
}