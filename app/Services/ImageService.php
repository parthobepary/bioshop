<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Store an image with optimization
     */
    public function store(UploadedFile $file, string $directory = 'images', array $options = []): string
    {
        $options = array_merge([
            'max_width' => 1200,
            'max_height' => 1200,
            'quality' => 85,
            'disk' => 'public',
        ], $options);

        // Generate unique filename
        $filename = $this->generateFilename($file);
        $path = "{$directory}/{$filename}";

        // Store original file (Laravel handles basic storage)
        Storage::disk($options['disk'])->putFileAs(
            $directory,
            $file,
            $filename
        );

        return $path;
    }

    /**
     * Store multiple images
     */
    public function storeMultiple(array $files, string $directory = 'images', array $options = []): array
    {
        $paths = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $paths[] = $this->store($file, $directory, $options);
            }
        }

        return $paths;
    }

    /**
     * Delete an image
     */
    public function delete(string $path, string $disk = 'public'): bool
    {
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }

        return false;
    }

    /**
     * Delete multiple images
     */
    public function deleteMultiple(array $paths, string $disk = 'public'): void
    {
        foreach ($paths as $path) {
            $this->delete($path, $disk);
        }
    }

    /**
     * Get the public URL for an image
     */
    public function url(string $path, string $disk = 'public'): string
    {
        return Storage::disk($disk)->url($path);
    }

    /**
     * Generate a unique filename
     */
    protected function generateFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        return Str::uuid() . '.' . $extension;
    }

    /**
     * Validate image file
     */
    public function validate(UploadedFile $file, array $rules = []): bool
    {
        $rules = array_merge([
            'max_size' => 5 * 1024 * 1024, // 5MB
            'allowed_types' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
        ], $rules);

        // Check file size
        if ($file->getSize() > $rules['max_size']) {
            return false;
        }

        // Check mime type
        if (!in_array($file->getMimeType(), $rules['allowed_types'])) {
            return false;
        }

        return true;
    }
}
