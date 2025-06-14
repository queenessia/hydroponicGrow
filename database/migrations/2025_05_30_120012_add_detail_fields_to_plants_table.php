<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('plants', function (Blueprint $table) {
        $table->text('cara_menanam')->nullable();
        $table->text('kebutuhan_lingkungan')->nullable();
        $table->text('waktu_panen')->nullable();
        $table->text('tips_perawatan')->nullable();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plants', function (Blueprint $table) {
            //
        });
    }
};
