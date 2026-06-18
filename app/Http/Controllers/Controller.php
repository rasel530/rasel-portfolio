<?php

namespace App\Http\Controllers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

abstract class Controller
{
    /**
     * Store an uploaded file while preserving the original filename.
     *
     * If a file with the same name already exists in the target directory,
     * a numeric suffix is appended (e.g. "resume.pdf" → "resume-1.pdf")
     * so the original name is kept readable but collisions are avoided.
     *
     * @param  string  $directory  e.g. "profile", "projects", "resume"
     * @return string  Relative path on the disk (e.g. "profile/photo.jpg")
     */
    protected function storeWithOriginalName(UploadedFile $file, string $directory, string $disk = 'public'): string
    {
        $originalName = $file->getClientOriginalName();

        // Strip any path components the client may have sent, keep only the basename.
        $filename = pathinfo($originalName, PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $extension = $extension !== '' ? '.' . $extension : '';

        $candidate = $filename . $extension;
        $counter = 1;

        // Avoid overwriting an existing file with the same name.
        while (Storage::disk($disk)->exists($directory . '/' . $candidate)) {
            $candidate = $filename . '-' . $counter . $extension;
            $counter++;
        }

        return $file->storeAs($directory, $candidate, $disk);
    }
}
