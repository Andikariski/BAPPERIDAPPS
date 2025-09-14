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
        Schema::create('tbl_foto_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fkid_kegiatan')->constrained('tbl_kegiatan', 'id')->onDelete('cascade');
            $table->string('nama_file');
            $table->string('path_file');
            $table->string('path_thumbnail')->nullable();
            $table->string('mime_type')->nullable();
            $table->bigInteger('ukuran_file')->nullable();
            $table->integer('urutan')->default(0);
            $table->text('caption')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->boolean('is_main')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_foto_kegiatan');
    }
};
