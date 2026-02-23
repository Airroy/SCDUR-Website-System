<?php

namespace App\Services;

use App\Models\Banner;
use App\Models\ScdYear;
use Illuminate\Database\Eloquent\Collection;

class BannerService
{
    public function __construct(
        protected FileUploadService $fileUploadService
    ) {}

    /**
     * Get all banners for a specific year
     */
    public function getBanners(ScdYear $year): Collection
    {
        return $year->banners()->orderBy('category')->orderBy('created_at', 'desc')->get();
    }

    /**
     * Create a new banner
     */
    public function create(array $data, $imageFile = null, $pdfFile = null): Banner
    {
        if ($imageFile) {
            $data['image_path'] = $this->fileUploadService->uploadImage($imageFile, 'banners');
        }

        if ($pdfFile && ($data['link_type'] ?? null) === 'pdf') {
            $data['pdf_path'] = $this->fileUploadService->uploadPdf($pdfFile, 'banner-pdfs');
        }

        // Clear PDF fields if link_type is not pdf
        if (($data['link_type'] ?? 'none') !== 'pdf') {
            $data['pdf_name'] = null;
            $data['pdf_path'] = null;
        }

        // Clear URL field if link_type is not url
        if (($data['link_type'] ?? 'none') !== 'url') {
            $data['link_url'] = null;
        }

        return Banner::create($data);
    }

    /**
     * Update an existing banner
     */
    public function update(Banner $banner, array $data, $imageFile = null, $pdfFile = null): Banner
    {
        if ($imageFile) {
            $data['image_path'] = $this->fileUploadService->replaceFile(
                $imageFile,
                $banner->image_path,
                'banners'
            );
        }

        if ($pdfFile && ($data['link_type'] ?? null) === 'pdf') {
            $data['pdf_path'] = $this->fileUploadService->replaceFile(
                $pdfFile,
                $banner->pdf_path,
                'banner-pdfs'
            );
        }

        // Clear PDF fields if link_type is not pdf
        if (($data['link_type'] ?? 'none') !== 'pdf') {
            // Delete old PDF if exists and link_type changed
            if ($banner->pdf_path) {
                $this->fileUploadService->delete($banner->pdf_path);
            }
            $data['pdf_name'] = null;
            $data['pdf_path'] = null;
        }

        // Clear URL field if link_type is not url
        if (($data['link_type'] ?? 'none') !== 'url') {
            $data['link_url'] = null;
        }

        $banner->update($data);
        return $banner->fresh();
    }

    /**
     * Delete a banner and its files
     */
    public function delete(Banner $banner): bool
    {
        $this->fileUploadService->delete($banner->image_path);
        $this->fileUploadService->delete($banner->pdf_path);

        return $banner->delete();
    }

    /**
     * Check if a category value is valid
     */
    public function isValidCategory(int $category): bool
    {
        return in_array($category, [0, 1]);
    }
}
