<?php

namespace App\Livewire\Backend;

use App\Models\ScdYear;
use App\Models\ContentNode;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class AnnouncementsIndex extends Component
{
    public $selectedYear;
    public $category = 'announcement';
    public $currentParentId = null;
    public $breadcrumbs = [];
    public $currentPage = 'announcements'; // เก็บว่าอยู่หน้าไหน

    protected $listeners = [
        'refreshAnnouncementTable' => '$refresh',
        'navigateToFolder' => 'navigateToFolder',
    ];

    public function mount($year = null)
    {
        $this->selectedYear = $year ? ScdYear::where('year', $year)->first() : null;
    }

    public function switchCategory($category)
    {
        $this->category = $category;
        $this->currentParentId = null;
        $this->breadcrumbs = [];
    }

    public function navigateToFolder($folderId)
    {
        $folder = ContentNode::find($folderId);
        if ($folder) {
            $this->currentParentId = $folderId;
            $this->buildBreadcrumbs($folder);
        }
    }

    public function navigateBack($parentId = null)
    {
        if ($parentId === null) {
            $this->currentParentId = null;
            $this->breadcrumbs = [];
        } else {
            $folder = ContentNode::find($parentId);
            if ($folder) {
                $this->currentParentId = $parentId;
                $this->buildBreadcrumbs($folder);
            }
        }
    }

    private function buildBreadcrumbs($folder)
    {
        $this->breadcrumbs = [];
        $current = $folder;
        
        while ($current) {
            array_unshift($this->breadcrumbs, [
                'id' => $current->id,
                'name' => $current->name,
            ]);
            $current = $current->parent;
        }
    }

    public function hasFilesInCurrentLevel()
    {
        if (!$this->selectedYear) return false;
        
        return ContentNode::where('scd_year_id', $this->selectedYear->id)
            ->where('category_group', $this->category)
            ->where('parent_id', $this->currentParentId)
            ->where('type', 'file')
            ->exists();
    }

    public function render()
    {
        $items = collect();
        $hasFiles = false;
        $hasFolders = false;
        
        if ($this->selectedYear) {
            $items = ContentNode::where('scd_year_id', $this->selectedYear->id)
                ->where('category_group', $this->category)
                ->where('parent_id', $this->currentParentId)
                ->orderBy('sequence')
                ->get();
            
            $hasFiles = $items->where('type', 'file')->isNotEmpty();
            $hasFolders = $items->where('type', 'folder')->isNotEmpty();
        }

        return view('livewire.backend.announcements-index', [
            'items' => $items,
            'hasFiles' => $hasFiles,
            'hasFolders' => $hasFolders,
        ]);
    }
}
