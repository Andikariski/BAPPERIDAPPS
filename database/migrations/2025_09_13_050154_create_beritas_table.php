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
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->enum('status_publikasi', ['draft', 'published', 'archive'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });

        // Pivot table for posts & tags

        Schema::create('tbl_berita_tag', function (Blueprint $table) {
            $table->foreignId('berita_id')
                ->constrained('tbl_berita')
                ->onDelete('cascade');
            $table->foreignId('tag_id')
                ->constrained('tbl_tag')
                ->onDelete('cascade');
            $table->primary(['berita_id', 'tag_id']); // composite PK
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
