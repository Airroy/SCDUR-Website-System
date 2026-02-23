<?php

namespace App\Services;

use App\Models\Announcement;
use App\Models\Order;
use App\Models\ContentSection;
use App\Models\ScdYear;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ContentNodeService
{
    public function __construct(
        protected FileUploadService $fileUploadService
    ) {}

    /**
     * Get the model class for a given category group
     */
    public function getModelClass(string $categoryGroup): string
    {
        return match ($categoryGroup) {
            'announcement' => Announcement::class,
            'order' => Order::class,
            'content', 'content_section' => ContentSection::class,
            default => ContentSection::class,
        };
    }

    /**
     * Get nodes for a specific year and parent
     */
    public function getNodes(ScdYear $year, ?int $parentId = null, string $categoryGroup = 'content_section'): Collection
    {
        $model = $this->getModelClass($categoryGroup);

        return $model::where('scd_year_id', $year->id)
            ->when(
                $parentId,
                fn($q) => $q->where('parent_id', $parentId),
                fn($q) => $q->whereNull('parent_id')
            )
            ->orderBy('sequence')
            ->get();
    }

    /**
     * Create a new node
     */
    public function create(array $data, $imageFile = null, $pdfFile = null, string $categoryGroup = 'content_section'): Model
    {
        if ($imageFile && $data['type'] === 'folder') {
            $data['image_path'] = $this->fileUploadService->uploadImage($imageFile, 'content-sections');
        }

        if ($pdfFile && $data['type'] === 'file') {
            $data['file_path'] = $this->fileUploadService->uploadPdf($pdfFile, 'content-sections');
        }

        $model = $this->getModelClass($categoryGroup);
        return $model::create($data);
    }

    /**
     * Update an existing node
     */
    public function update(Model $node, array $data, $imageFile = null, $pdfFile = null): Model
    {
        if ($imageFile && $data['type'] === 'folder') {
            $data['image_path'] = $this->fileUploadService->replaceFile(
                $imageFile,
                $node->image_path,
                'content-sections'
            );
        }

        if ($pdfFile && $data['type'] === 'file') {
            $data['file_path'] = $this->fileUploadService->replaceFile(
                $pdfFile,
                $node->file_path,
                'content-sections'
            );
        }

        $node->update($data);
        return $node->fresh();
    }

    /**
     * Delete a node and its files
     */
    public function delete(Model $node): bool
    {
        $this->fileUploadService->delete($node->image_path);
        $this->fileUploadService->delete($node->file_path);

        return $node->delete();
    }

    /**
     * Get breadcrumbs for navigation
     */
    public function getBreadcrumbs(?Model $node): array
    {
        if (!$node) return [];

        $path = [];
        while ($node) {
            array_unshift($path, $node);
            $node = $node->parent;
        }
        return $path;
    }

    /**
     * Check if a sequence is unique within the parent
     */
    public function isSequenceUnique(ScdYear $year, int $sequence, ?int $parentId, ?int $excludeId = null, string $categoryGroup = 'content_section'): bool
    {
        $model = $this->getModelClass($categoryGroup);

        return !$model::where('scd_year_id', $year->id)
            ->where('parent_id', $parentId)
            ->where('sequence', $sequence)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();
    }

    /**
     * Get next available sequence number
     */
    public function getNextSequence(ScdYear $year, ?int $parentId = null, string $categoryGroup = 'content_section'): int
    {
        $model = $this->getModelClass($categoryGroup);

        $maxSequence = $model::where('scd_year_id', $year->id)
            ->where('parent_id', $parentId)
            ->max('sequence');

        return ($maxSequence ?? 0) + 1;
    }

    /**
     * Check if level has folders
     */
    public function hasFolders(ScdYear $year, ?int $parentId = null, string $categoryGroup = 'content_section'): bool
    {
        $model = $this->getModelClass($categoryGroup);

        return $model::where('scd_year_id', $year->id)
            ->where('parent_id', $parentId)
            ->where('type', 'folder')
            ->exists();
    }

    /**
     * Check if level has files
     */
    public function hasFiles(ScdYear $year, ?int $parentId = null, string $categoryGroup = 'content_section'): bool
    {
        $model = $this->getModelClass($categoryGroup);

        return $model::where('scd_year_id', $year->id)
            ->where('parent_id', $parentId)
            ->where('type', 'file')
            ->exists();
    }
}
