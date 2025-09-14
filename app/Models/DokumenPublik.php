<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DokumenPublik extends Model
{
    use HasFactory;
    protected $table = 'tbl_dokumen_publik';
    protected $fillable = [
        'fkid_bidang',
        'nama_dokumen',
        'deskripsi_dokumen'
    ];

    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class, 'fkid_bidang');
    }
}
