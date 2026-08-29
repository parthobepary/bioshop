<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    /**
     * Generate a presigned PUT URL so the browser can upload a file
     * directly to DigitalOcean Spaces (S3-compatible), then return the
     * stored object path for persistence.
     */
    public function presignedUrl(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        // Only keep a safe basename; the client already sends a random name.
        $safeName = Str::of($validated['name'])
            ->replace('\\', '/')
            ->afterLast('/')
            ->replaceMatches('/[^A-Za-z0-9._-]/', '')
            ->__toString();

        if ($safeName === '') {
            $safeName = Str::random(32);
        }

        $key = 'uploads/'.now()->format('Y/m').'/'.$safeName;

        $client = Storage::disk('s3')->getClient();
        // public-read so uploaded images are viewable on the public shop.
        // The browser must send a matching `x-amz-acl: public-read` header.
        $command = $client->getCommand('PutObject', [
            'Bucket' => config('filesystems.disks.s3.bucket'),
            'Key' => $key,
            'ACL' => 'public-read',
        ]);

        $presignedRequest = $client->createPresignedRequest($command, '+15 minutes');

        return response()->json([
            'url' => (string) $presignedRequest->getUri(),
            'path' => $key,
            'publicUrl' => Storage::disk('s3')->url($key),
        ]);
    }
}
