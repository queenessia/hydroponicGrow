<?php
// app/Models/Post.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'content',
        'images',
        'parent_id',
        'likes_count',
        'replies_count'
    ];

    protected $casts = [
        'images' => 'array'
    ];

    protected $with = ['member']; // Always load member relationship

    // Relationships
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function parent()
    {
        return $this->belongsTo(Post::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Post::class, 'parent_id')->latest();
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    // Scopes
    public function scopeMainPosts($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeReplies($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function scopeWithCounts($query)
    {
        return $query->withCount(['likes', 'replies']);
    }

    // Helper methods
    public function isLikedBy($memberId)
    {
        return $this->likes()->where('member_id', $memberId)->exists();
    }

    public function isBookmarkedBy($memberId)
    {
        return $this->bookmarks()->where('member_id', $memberId)->exists();
    }

    public function isMainPost()
    {
        return is_null($this->parent_id);
    }

    public function isReply()
    {
        return !is_null($this->parent_id);
    }
}


?>