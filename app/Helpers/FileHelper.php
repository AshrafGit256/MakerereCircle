<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class FileHelper
{
    public static function storeInDatabase(UploadedFile $file, $directory = 'uploads')
    {
        // Read file contents
        $contents = file_get_contents($file->getRealPath());

        // Store in database
        $media = \App\Models\Media::create([
            'disk' => 'database',
            'directory' => $directory,
            'filename' => $file->getClientOriginalName(),
            'extension' => $file->getClientOriginalExtension(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'data' => base64_encode($contents),
            'aggregate_type' => self::getAggregateType($file->getMimeType()),
        ]);

        return $media->id;
    }

    public static function getFromDatabase($mediaId)
    {
        $media = \App\Models\Media::find($mediaId);
        if (!$media) {
            return null;
        }

        return [
            'content' => base64_decode($media->data),
            'mime_type' => $media->mime_type,
            'filename' => $media->filename,
        ];
    }

    private static function getAggregateType($mimeType)
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        } elseif (str_starts_with($mimeType, 'video/')) {
            return 'video';
        } elseif (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        } else {
            return 'file';
        }
    }
}
