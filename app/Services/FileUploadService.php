<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class FileUploadService
{
    /**
     * Upload an image file
     */
    public function uploadImage(UploadedFile|TemporaryUploadedFile $file, string $directory = 'uploads'): string
    {
        return $file->store($directory, 'public');
    }

    /**
     * Upload a PDF file
     */
    public function uploadPdf(UploadedFile|TemporaryUploadedFile $file, string $directory = 'pdfs'): string
    {
        return $file->store($directory, 'public');
    }

    /**
     * Delete a file from storage
     */
    public function delete(?string $path): bool
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }

    /**
     * Replace an existing file with a new one
     */
    public function replaceFile(
        UploadedFile|TemporaryUploadedFile|null $newFile,
        ?string $existingPath,
        string $directory
    ): ?string {
        if (!$newFile) {
            return $existingPath;
        }

        $this->delete($existingPath);
        return $newFile->store($directory, 'public');
    }

    /**
     * Check if a file exists in storage
     */
    public function exists(?string $path): bool
    {
        return $path && Storage::disk('public')->exists($path);
    }

    /**
     * Get the public URL for a file
     */
    public function getUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }
        return Storage::url($path);
    }
}
