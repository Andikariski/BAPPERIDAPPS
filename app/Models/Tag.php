<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $table = "tbl_tag";
    protected $fillable = ['name', 'slug'];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
    public function berita(): BelongsToMany
    {
        return $this->belongsToMany(Berita::class, 'tbl_berita_tag', 'tag_id', 'berita_id')->withTimestamps();
    }
}
