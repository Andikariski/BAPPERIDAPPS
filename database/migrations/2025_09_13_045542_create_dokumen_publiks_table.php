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
        Schema::create('tbl_dokumen_publik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fkid_bidang')->constrained('tbl_bidang', 'id')->onDelete('cascade');
            $table->string('nama_dokumen');
            $table->text('deskripsi_dokumen');
            $table->string('file_path'); // Path file asli
            $table->string('file_name'); // Nama file original
            $table->string('file_type'); // pdf, docx, xlsx, etc
            $table->integer('file_size'); // dalam bytes
            $table->string('thumbnail_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_dokumen_publik');
    }
};
