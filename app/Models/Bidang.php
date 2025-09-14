<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bidang extends Model
{
    use HasFactory;
    protected $table = 'tbl_bidang';
    protected $fillable = ['nama_bidang'];

    public function pegawai(): HasMany
    {
        return $this->hasMany(Pegawai::class, 'fkid_bidang');
    }
    public function kegiatan(): HasMany
    {
        return $this->hasMany(Kegiatan::class, 'fkid_bidang');
    }
    public function dokumenPublik(): HasMany
    {
        return $this->hasMany(DokumenPublik::class, 'fkid_bidang');
    }
    public function berita(): HasMany
    {
        return $this->hasMany(Pegawai::class, 'fkid_bidang');
    }
}
