<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pegawai extends Model
{
    use HasFactory;
    protected $table = 'tbl_pegawai';
    protected $fillable = [
        'fkid_bidang',
        'fkid_jabatan',
        'nip',
        'nama_pegawai',
        'foto_profile'
    ];

    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class, 'fkid_bidang');
    }
    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'fkid_jabatan');
    }
}
