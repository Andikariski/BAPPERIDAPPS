<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Berita extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "tbl_berita";

    protected $fillable = [
        'fkid_bidang',
        'judul_berita',
        'slug',
        'konten_berita',
        'foto_thumbnail',
        'author_id',
        'status_publikasi'
    ];

    protected $casts = [
        'status_publikasi' => 'string'
    ];

    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class, 'fkid_bidang');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'tbl_berita_tag', 'berita_id', 'tag_id');
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
