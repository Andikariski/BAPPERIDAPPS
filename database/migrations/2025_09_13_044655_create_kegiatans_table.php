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
        Schema::create('tbl_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fkid_bidang')->constrained('tbl_bidang', 'id')->onDelete('cascade');
            $table->string('nama_kegiatan');
            $table->text('deskripsi_kegiatan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_kegiatan');
    }
};
