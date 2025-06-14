<?php
// Migration 1: posts table
// php artisan make:migration create_posts_table

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->text('content');
            $table->json('images')->nullable(); // Store multiple image paths as JSON
            $table->foreignId('parent_id')->nullable()->constrained('posts')->onDelete('cascade'); // For replies
            $table->integer('likes_count')->default(0);
            $table->integer('replies_count')->default(0);
            $table->timestamps();
            
            $table->index(['member_id', 'created_at']);
            $table->index('parent_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }
}