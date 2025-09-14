<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class FotoKegiatan extends Model
{
    use HasFactory;
    protected $table = 'tbl_foto_kegiatan';
    protected $fillable = [
        'fkid_kegiatan',
        'nama_file',
        'path_file',
        'path_thumbnail',
        'mime_type',
        'ukuran_file',
        'urutan',
        'caption',
        'width',
        'height',
        'is_main'
    ];
    protected $casts = [
        'is_main' => 'boolean'
    ];

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class, 'fkid_kegiatan');
    }

    // helper
    public function getUrlAttribute(): string
    {
        return Storage::url($this->path_file);
    }
    public function hapusFile(): bool
    {
        $deleted = true;

        if (Storage::exists($this->path_file)) {
            $deleted = Storage::delete($this->path_file);
        }

        if ($this->path_thumbnail && Storage::exists($this->path_thumbnail)) {
            Storage::delete($this->path_thumbnail);
        }

        return $deleted;
    }
    public function getUkuranFileFormattedAttribute(): string
    {
        $bytes = $this->ukuran_file;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
    private static function getImageManager()
    {
        $driver = config('image.driver', 'gd');
        return $driver === 'imagick'
            ? ImageManager::imagick()
            : ImageManager::gd();
    }
    private function resizeImage(string $path, ?int $maxWidth = null, ?int $maxHeight = null)
    {
        $maxWidth = $maxWidth ?: config('image.resize.max_width', 1920);
        $maxHeight = $maxHeight ?: config('image.resize.max_height', 1080);
        $quality = config('image.quality.resize', 85);

        $fullPath = storage_path(config('image.paths.storage', 'app/public') . '/' . $path);

        $image = self::getImageManager()->read($fullPath);

        if ($image->width() > $maxWidth || $image->height() > $maxHeight) {
            $image->scaleDown($maxWidth, $maxHeight);

            $encoded = $image->toJpeg(quality: $quality);
            file_put_contents($fullPath, $encoded);
        }
    }
    public static function addWatermark(string $imagePath): void
    {
        $config = config('image.watermark');
        $watermarkText = $config['text'];
        $fullPath = storage_path(config('image.paths.storage', 'app/public') . '/' . $imagePath);

        $image = self::getImageManager()->read($fullPath);

        $image->text(
            $watermarkText,
            $image->width() - $config['position']['x_offset'],
            $image->height() - $config['position']['y_offset'],
            function ($font) use ($config) {
                if ($config['font_path'] && file_exists($config['font_path'])) {
                    $font->filename($config['font_path']);
                }
                $font->size($config['font_size']);
                $font->color($config['color']);
                $font->align($config['position']['align']);
                $font->valign($config['position']['valign']);
                $font->angle($config['angle']);
            }
        );

        $quality = config('image.quality.default', 90);
        $encoded = $image->toJpeg(quality: $quality);
        file_put_contents($fullPath, $encoded);
    }
    public static function generateThumbnail(string $originalPath, ?int $width = null, ?int $height = null): string
    {
        $width = $width ?: config('image.thumbnail.width', 300);
        $height = $height ?: config('image.thumbnail.height', 200);
        $quality = config('image.quality.thumbnail', 80);

        $thumbnailPath = str_replace('/kegiatan/', '/kegiatan/thumbnails/', $originalPath);
        $thumbnailFullPath = storage_path(config('image.paths.storage', 'app/public') . '/' . $thumbnailPath);

        // Buat direktori thumbnail jika belum ada
        $thumbnailDir = dirname($thumbnailFullPath);
        if (!file_exists($thumbnailDir)) {
            mkdir($thumbnailDir, 0755, true);
        }

        $image = self::getImageManager()->read(storage_path(config('image.paths.storage', 'app/public') . '/' . $originalPath));

        // Gunakan method dari konfigurasi
        $method = config('image.thumbnail.method', 'cover');
        switch ($method) {
            case 'contain':
                $image->contain($width, $height);
                break;
            case 'scaleDown':
                $image->scaleDown($width, $height);
                break;
            case 'cover':
            default:
                $image->cover($width, $height);
                break;
        }

        $encoded = $image->toJpeg(quality: $quality);
        file_put_contents($thumbnailFullPath, $encoded);

        return $thumbnailPath;
    }
}
