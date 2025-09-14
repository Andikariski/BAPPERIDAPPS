<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_berita', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fkid_bidang')->constrained('tbl_bidang', 'id')->onDelete('cascade');
            $table->string('judul_berita');
            $table->string('slug')->unique();
            $table->longText('konten_berita');
            $table->string('foto_thumbnail')->nullable();
            $table->string('tags_berita')->nullable();
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->enum('status_publikasi', ['draft', 'published', 'archive'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_berita');
    }
};
