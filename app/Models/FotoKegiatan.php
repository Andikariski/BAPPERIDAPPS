<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Typography\FontFactory;

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

    // Scope untuk sorting berdasarkan urutan
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan', 'asc');
    }

    // Scope untuk foto utama
    public function scopeMainPhoto($query)
    {
        return $query->where('is_main', true);
    }

    // Get foto berikutnya berdasarkan urutan
    public function getNextPhoto()
    {
        return static::where('fkid_kegiatan', $this->fkid_kegiatan)
            ->where('urutan', '>', $this->urutan)
            ->orderBy('urutan', 'asc')
            ->first();
    }

    // Get foto sebelumnya berdasarkan urutan
    public function getPreviousPhoto()
    {
        return static::where('fkid_kegiatan', $this->fkid_kegiatan)
            ->where('urutan', '<', $this->urutan)
            ->orderBy('urutan', 'desc')
            ->first();
    }

    // Get posisi foto dalam galeri
    public function getPositionAttribute()
    {
        return static::where('fkid_kegiatan', $this->fkid_kegiatan)
            ->where('urutan', '<=', $this->urutan)
            ->count();
    }

    // helper
    public function getUrlAttribute(): string
    {
        return Storage::url($this->path_file);
    }
    public function hapusFile(): bool
    {
        $deleted = true;

        if ($this->path_file && Storage::disk('public')->exists($this->path_file)) {
            $deleted = Storage::disk('public')->delete($this->path_file);
        }

        if ($this->path_thumbnail && Storage::disk('public')->exists($this->path_thumbnail)) {
            Storage::disk('public')->delete($this->path_thumbnail);
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
    private function resizeImage(string $path, ?int $maxWidth = null, ?int $maxHeight = null): void
    {
        $maxWidth = $maxWidth ?: config('image.resize.max_width', 100);
        $maxHeight = $maxHeight ?: config('image.resize.max_height', 100);
        $quality = config('image.quality.resize', 85);

        $fullPath = Storage::disk('public')->path($path);
        $image = self::getImageManager()->read($fullPath);

        if ($image->width() > $maxWidth || $image->height() > $maxHeight) {
            $image = $image->scaleDown($maxWidth, $maxHeight); // harus ditampung!

            // Simpan dalam format asli
            $format = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
            $encoded = match ($format) {
                'png'  => $image->toPng(),
                'webp' => $image->toWebp(quality: $quality),
                default => $image->toJpeg(quality: $quality),
            };

            Storage::disk('public')->put($path, (string) $encoded);
        }
    }

    public static function addWatermark(string $imagePath): void
    {
        $config = config('image.watermark');
        $watermarkText = $config['text'];

        $fullPath = Storage::disk('public')->path($imagePath);
        $image = self::getImageManager()->read($fullPath);

        $image->text(
            $watermarkText,
            $image->width() - $config['position']['x_offset'],
            $image->height() - $config['position']['y_offset'],
            function (FontFactory $font) use ($config) {
                if ($config['font_path'] && file_exists($config['font_path'])) {
                    $font->filename($config['font_path']);
                }
                $font->size($config['font_size']);
                $font->color($config['color']);
                $font->stroke($config['stroke']);
                $font->align($config['position']['align']);
                $font->valign($config['position']['valign']);
                $font->angle($config['angle']);
            }
        );

        $quality = config('image.quality.default', 90);
        $format = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

        $encoded = match ($format) {
            'png'  => $image->toPng(),
            'webp' => $image->toWebp(quality: $quality),
            default => $image->toJpeg(quality: $quality),
        };

        Storage::disk('public')->put($imagePath, (string) $encoded);
    }


    public static function generateThumbnail(string $originalPath, ?int $width = null, ?int $height = null): string
    {
        $width = $width ?: config('image.thumbnail.width', 300);
        $height = $height ?: config('image.thumbnail.height', 200);
        $quality = config('image.quality.thumbnail', 80);

        // Pastikan replace benar
        $thumbnailPath = str_replace("kegiatan/", "kegiatan/thumbnails/", $originalPath);

        // Path full
        $thumbnailFullPath = Storage::disk('public')->path($thumbnailPath);

        // Buat direktori thumbnail kalau belum ada
        $thumbnailDir = dirname($thumbnailFullPath);
        if (!is_dir($thumbnailDir)) {
            mkdir($thumbnailDir, 0755, true);
        }

        $image = self::getImageManager()->read(Storage::disk('public')->path($originalPath));

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
        Storage::disk('public')->put($thumbnailPath, (string) $encoded);

        return $thumbnailPath; // relatif ke disk public
    }
}
