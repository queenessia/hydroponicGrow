<?php
// Migration 1: posts table
// php artisan make:migration create_posts_table

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



class CreateBookmarksTable extends Migration
{
    public function up()
    {
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['member_id', 'post_id']); // Prevent duplicate bookmarks
            $table->index('post_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookmarks');
    }
}
?>