<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Berita extends Model
{
    use HasFactory;
    protected $table = "tbl_berita";
    protected $fillable = [
        'fkid_bidang',
        'judul_berita',
        'konten_berita',
        'foto_thumbnail',
        'tags_berita',
        'status_publikasi'
    ];
    protected $cast = [
        'status_publikasi' => 'string'
    ];

    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class, 'fkid_bidang');
    }

    public function scopePublished($query)
    {
        return $query->where('status_publikasi', 'published');
    }
    public function scopeDraft($query)
    {
        return $query->where('status_publikasi', 'draft');
    }
}
