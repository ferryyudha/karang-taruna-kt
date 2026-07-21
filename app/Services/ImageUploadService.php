<?php

namespace App\Services;

use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadService
{
    /**
     * Upload dan auto-resize gambar ke storage publik.
     *
     * @param UploadedFile $file       File yang diupload
     * @param string       $folder     Folder tujuan di storage/app/public (e.g. 'anggota', 'kegiatan')
     * @param int          $maxWidth   Lebar maksimum dalam pixel (default: 1920)
     * @param int          $maxHeight  Tinggi maksimum dalam pixel (default: 1080)
     * @param int          $quality    Kualitas kompresi JPEG/WebP 0-100 (default: 80)
     * @return string  Path relatif file yang tersimpan (untuk disimpan ke database)
     */
    public static function upload(
        UploadedFile $file,
        string $folder,
        int $maxWidth = 1920,
        int $maxHeight = 1080,
        int $quality = 82
    ): string {
        // Cek dimensi gambar dulu lewat header file (ringan, tidak decode penuh ke memory)
        // sebelum Intervention Image membaca seluruh isinya. Ini mencegah orang upload
        // file berukuran kecil tapi resolusi ekstrem (misal 20000x20000px) yang bisa
        // menghabiskan memory server saat di-decode.
        $dimensions = @getimagesize($file->getRealPath());
        if ($dimensions === false) {
            throw new \InvalidArgumentException('File bukan gambar yang valid.');
        }
        [$rawWidth, $rawHeight] = $dimensions;
        $maxRawDimension = 6000; // batas aman sebelum di-resize
        if ($rawWidth > $maxRawDimension || $rawHeight > $maxRawDimension) {
            throw new \InvalidArgumentException('Resolusi gambar terlalu besar (maksimal ' . $maxRawDimension . 'px di setiap sisi).');
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $filename = Str::uuid() . '.' . ($extension === 'jpeg' ? 'jpg' : $extension);
        $storagePath = $folder . '/' . $filename;

        // Baca gambar dengan Intervention Image
        $image = Image::read($file->getRealPath());

        // Auto-resize jika resolusi melebihi batas maksimal
        // Scale Down: memperkecil proporsional jika width > maxWidth ATAU height > maxHeight
        // Scale Up tidak dilakukan (gambar kecil tidak diperbesar)
        if ($image->width() > $maxWidth || $image->height() > $maxHeight) {
            $image->scaleDown($maxWidth, $maxHeight);
        }

        // Encode ke format asli dengan kompresi
        $encoded = match ($extension) {
            'png'       => $image->toPng(),
            'gif'       => $image->toGif(),
            'webp'      => $image->toWebp($quality),
            default     => $image->toJpeg($quality), // jpg, jpeg
        };

        Storage::disk('public')->put($storagePath, (string) $encoded);

        return $storagePath;
    }

    /**
     * Upload foto profil/thumbnail berukuran kecil (maks 800x800).
     */
    public static function uploadThumbnail(UploadedFile $file, string $folder): string
    {
        return self::upload($file, $folder, maxWidth: 800, maxHeight: 800, quality: 85);
    }

    /**
     * Upload foto dokumentasi/galeri berukuran sedang (maks 1920x1080).
     */
    public static function uploadPhoto(UploadedFile $file, string $folder): string
    {
        return self::upload($file, $folder, maxWidth: 1920, maxHeight: 1080, quality: 80);
    }
}
