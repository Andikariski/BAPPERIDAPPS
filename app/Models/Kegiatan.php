<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kegiatan extends Model
{
    use HasFactory;
    protected $table = 'tbl_kegiatan';
    protected $fillable = [
        'fkid_bidang',
        'nama_kegiatan',
        'deskripsi_kegiatan'
    ];

    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class, 'fkid_bidang');
    }
    public function fotoKegiatan(): HasMany
    {
        return $this->hasMany(FotoKegiatan::class, 'fkid_kegiatan')->orderBy('urutan');
    }
    public function getFotoUtama()
    {
        return $this->fotoKegiatan()->where('is_main', true)->first() ?: $this->fotoKegiatan()->first();
    }
    public function getTotalFotoAttribute(): int
    {
        return $this->fotoKegiatan()->count();
    }
}
