<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Service untuk generate thumbnail dari PDF menggunakan GD Library
 *
 * REQUIREMENTS:
 * - PHP GD Extension (biasanya sudah include)
 * - Ghostscript: sudo apt-get install ghostscript
 *
 * NOTE: GD tidak bisa membaca PDF langsung, jadi kita gunakan
 * Ghostscript untuk convert PDF ke image terlebih dahulu
 */
class PdfThumbnailService
{
    /**
     * Generate thumbnail dari PDF menggunakan GD
     *
     * @param string $pdfPath Path ke file PDF di storage
     * @param int $page Halaman yang akan dijadikan thumbnail (default: 0 = halaman pertama)
     * @param int $width Lebar thumbnail (default: 400px)
     * @param int $quality Quality JPEG (1-100, default: 85)
     * @return string|null Path thumbnail yang berhasil di-generate
     */
    public function generateThumbnail(
        string $pdfPath,
        int $page = 0,
        int $width = 400,
        int $quality = 85
    ): ?string {
        // Cek apakah GD tersedia
        if (!extension_loaded('gd')) {
            Log::warning('GD extension not loaded. Cannot generate PDF thumbnail.');
            return null;
        }

        try {
            $fullPath = Storage::disk('public')->path($pdfPath);

            if (!file_exists($fullPath)) {
                throw new \Exception('PDF file not found: ' . $fullPath);
            }

            // Step 1: Convert PDF ke PNG menggunakan Ghostscript
            $tempImagePath = $this->convertPdfToImage($fullPath, $page);

            if (!$tempImagePath || !file_exists($tempImagePath)) {
                throw new \Exception('Failed to convert PDF to image');
            }

            // Step 2: Load image dengan GD dan resize
            $image = $this->createImageFromFile($tempImagePath);

            if (!$image) {
                @unlink($tempImagePath); // Hapus temp file
                throw new \Exception('Failed to create image resource from temp file');
            }

            // Get original dimensions
            $originalWidth = imagesx($image);
            $originalHeight = imagesy($image);

            // Calculate new dimensions (maintain aspect ratio)
            $height = (int) (($width / $originalWidth) * $originalHeight);

            // Create thumbnail
            $thumbnail = imagecreatetruecolor($width, $height);

            // Preserve transparency for PNG (optional, karena kita save ke JPG)
            imagealphablending($thumbnail, false);
            imagesavealpha($thumbnail, true);

            // Resize image
            imagecopyresampled(
                $thumbnail,
                $image,
                0,
                0,
                0,
                0,
                $width,
                $height,
                $originalWidth,
                $originalHeight
            );

            // Generate thumbnail filename
            $thumbnailName = Str::slug(pathinfo($pdfPath, PATHINFO_FILENAME)) . '_thumb_' . time() . '.jpg';
            $thumbnailPath = 'thumbnails/' . $thumbnailName;
            $thumbnailFullPath = Storage::disk('public')->path($thumbnailPath);

            // Ensure thumbnails directory exists
            $thumbnailDir = dirname($thumbnailFullPath);
            if (!file_exists($thumbnailDir)) {
                mkdir($thumbnailDir, 0755, true);
            }

            // Save thumbnail as JPEG
            imagejpeg($thumbnail, $thumbnailFullPath, $quality);

            // Clean up
            imagedestroy($image);
            imagedestroy($thumbnail);
            @unlink($tempImagePath); // Hapus temp file

            return $thumbnailPath;
        } catch (\Exception $e) {
            Log::error('Failed to generate PDF thumbnail: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Convert PDF page ke image menggunakan Ghostscript
     *
     * @param string $pdfPath Full path ke PDF
     * @param int $page Page number (0-based)
     * @return string|null Path ke temporary image
     */
    private function convertPdfToImage(string $pdfPath, int $page = 0): ?string
    {
        // Cek apakah Ghostscript tersedia
        $gsPath = $this->findGhostscript();

        if (!$gsPath) {
            Log::warning('Ghostscript not found. Cannot convert PDF to image.');
            return null;
        }

        // Create temp filename
        $tempImage = sys_get_temp_dir() . '/pdf_thumb_' . uniqid() . '.png';

        // Ghostscript command
        // -dFirstPage dan -dLastPage dimulai dari 1 (bukan 0)
        $pageNum = $page + 1;

        $command = sprintf(
            '%s -dSAFER -dBATCH -dNOPAUSE -sDEVICE=png16m -r150 -dFirstPage=%d -dLastPage=%d -sOutputFile=%s %s 2>&1',
            escapeshellarg($gsPath),
            $pageNum,
            $pageNum,
            escapeshellarg($tempImage),
            escapeshellarg($pdfPath)
        );

        exec($command, $output, $returnCode);

        if ($returnCode !== 0 || !file_exists($tempImage)) {
            Log::error('Ghostscript conversion failed', [
                'command' => $command,
                'output' => $output,
                'return_code' => $returnCode
            ]);
            return null;
        }

        return $tempImage;
    }

    /**
     * Find Ghostscript binary path
     *
     * @return string|null
     */
    private function findGhostscript(): ?string
    {
        $possiblePaths = [
            '/usr/bin/gs',           // Linux
            '/usr/local/bin/gs',     // Linux/Mac
            'C:\Program Files\gs\gs10.02.1\bin\gswin64c.exe',  // Windows 64-bit
            'C:\Program Files\gs\gs10.06.0\bin\gswin64c.exe',
            'C:\Program Files\gs\gs10.06.0\bin\gswin64.exe',
        ];

        foreach ($possiblePaths as $path) {
            if (@is_executable($path)) {
                return $path;
            }
        }

        // Try to find in PATH
        exec('which gs 2>/dev/null', $output);
        if (!empty($output[0]) && is_executable($output[0])) {
            return $output[0];
        }

        // Windows - try 'where' command
        exec('where gs 2>nul', $output);
        if (!empty($output[0]) && file_exists($output[0])) {
            return $output[0];
        }

        return null;
    }

    /**
     * Create GD image resource from file
     *
     * @param string $filePath
     * @return \GdImage|false
     */
    private function createImageFromFile(string $filePath)
    {
        $imageInfo = getimagesize($filePath);

        if (!$imageInfo) {
            return false;
        }

        $mimeType = $imageInfo['mime'];

        switch ($mimeType) {
            case 'image/jpeg':
                return imagecreatefromjpeg($filePath);
            case 'image/png':
                return imagecreatefrompng($filePath);
            case 'image/gif':
                return imagecreatefromgif($filePath);
            case 'image/webp':
                return imagecreatefromwebp($filePath);
            default:
                return false;
        }
    }

    /**
     * Check if service can generate thumbnails
     *
     * @return array Status check
     */
    public function checkRequirements(): array
    {
        return [
            'gd_loaded' => extension_loaded('gd'),
            'ghostscript_available' => $this->findGhostscript() !== null,
            'ghostscript_path' => $this->findGhostscript(),
        ];
    }
}
