<?php

namespace App\Services;

use App\Models\ContentNode;
use App\Models\ScdYear;
use Illuminate\Database\Eloquent\Collection;

class ContentNodeService
{
    public function __construct(
        protected FileUploadService $fileUploadService
    ) {}

    /**
     * Get nodes for a specific year and parent
     */
    public function getNodes(ScdYear $year, ?int $parentId = null, string $categoryGroup = 'content_section'): Collection
    {
        return ContentNode::where('scd_year_id', $year->id)
            ->where('category_group', $categoryGroup)
            ->when($parentId, 
                fn($q) => $q->where('parent_id', $parentId),
                fn($q) => $q->whereNull('parent_id')
            )
            ->orderBy('sequence')
            ->get();
    }

    /**
     * Create a new content node
     */
    public function create(array $data, $imageFile = null, $pdfFile = null): ContentNode
    {
        if ($imageFile && $data['type'] === 'folder') {
            $data['image_path'] = $this->fileUploadService->uploadImage($imageFile, 'content-sections');
        }

        if ($pdfFile && $data['type'] === 'file') {
            $data['file_path'] = $this->fileUploadService->uploadPdf($pdfFile, 'content-sections');
        }

        return ContentNode::create($data);
    }

    /**
     * Update an existing content node
     */
    public function update(ContentNode $node, array $data, $imageFile = null, $pdfFile = null): ContentNode
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
     * Delete a content node and its files
     */
    public function delete(ContentNode $node): bool
    {
        $this->fileUploadService->delete($node->image_path);
        $this->fileUploadService->delete($node->file_path);
        
        return $node->delete();
    }

    /**
     * Get breadcrumbs for navigation
     */
    public function getBreadcrumbs(?ContentNode $node): array
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
        return !ContentNode::where('scd_year_id', $year->id)
            ->where('category_group', $categoryGroup)
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
        $maxSequence = ContentNode::where('scd_year_id', $year->id)
            ->where('category_group', $categoryGroup)
            ->where('parent_id', $parentId)
            ->max('sequence');

        return ($maxSequence ?? 0) + 1;
    }

    /**
     * Check if level has folders
     */
    public function hasFolders(ScdYear $year, ?int $parentId = null, string $categoryGroup = 'content_section'): bool
    {
        return ContentNode::where('scd_year_id', $year->id)
            ->where('category_group', $categoryGroup)
            ->where('parent_id', $parentId)
            ->where('type', 'folder')
            ->exists();
    }

    /**
     * Check if level has files
     */
    public function hasFiles(ScdYear $year, ?int $parentId = null, string $categoryGroup = 'content_section'): bool
    {
        return ContentNode::where('scd_year_id', $year->id)
            ->where('category_group', $categoryGroup)
            ->where('parent_id', $parentId)
            ->where('type', 'file')
            ->exists();
    }
}
