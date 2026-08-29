<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class Media
{
    /**
     * Decide which disk a stored path lives on. New uploads go to
     * DigitalOcean Spaces (s3) under "uploads/"; older files remain on
     * the local "public" disk.
     */
    public static function disk(string $path): string
    {
        return str_starts_with($path, 'uploads/') ? 's3' : 'public';
    }

    /**
     * Resolve a stored path to a public URL (handles both disks and full URLs).
     */
    public static function url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        return Storage::disk(self::disk($path))->url($path);
    }

    /**
     * Delete a stored file from whichever disk it belongs to.
     */
    public static function delete(?string $path): void
    {
        if (! $path) {
            return;
        }

        Storage::disk(self::disk($path))->delete($path);
    }
}
