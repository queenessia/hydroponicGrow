<?php


// app/Models/Bookmark.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'post_id'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}

?>