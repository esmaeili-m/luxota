<?php
namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Uploader
{
    public static function uploadImage(UploadedFile $file, string $directory = 'uploads'): string
    {
        return $file->store($directory, 'public');
    }

    public static function deleteImage(string $path): void
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
