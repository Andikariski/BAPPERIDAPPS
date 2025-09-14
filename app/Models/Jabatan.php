<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jabatan extends Model
{
    use HasFactory;
    protected $table = 'tbl_jabatan';
    protected $fillable = [
        'nama_jabatan',
        'kelas_jabatan'
    ];

    public function pegawai(): HasMany
    {
        return $this->hasMany(Pegawai::class, 'fkid_jabatan');
    }
}
