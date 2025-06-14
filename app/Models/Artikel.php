<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    use HasFactory;

    protected $fillable = [
        'thumbnail',
        'published_date',
        'source',
        'title',
        'description',
        'link'
    ];

    protected $casts = [
        'published_date' => 'date'
    ];
}