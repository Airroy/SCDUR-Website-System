<?php

namespace App\Livewire\Backend;

use App\Models\ScdYear;
use App\Models\ContentNode;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class ContentsIndex extends Component
{
    public $currentPage = 'contents';
    public $selectedYear;
    public $currentParentId = null;
    public $breadcrumbs = [];

    protected $listeners = [
        'refreshContentTable' => '$refresh',
        'viewFolder' => 'navigateToFolder',
    ];

    public function mount($year = null)
    {
        $this->selectedYear = $year ? ScdYear::where('year', $year)->first() : null;
    }

    public function navigateToFolder($folderId)
    {
        $folder = ContentNode::findOrFail($folderId);
        $this->currentParentId = $folderId;
        $this->buildBreadcrumbs($folderId);
    }

    public function navigateBack($parentId = null)
    {
        $this->currentParentId = $parentId;
        if ($parentId) {
            $this->buildBreadcrumbs($parentId);
        } else {
            $this->breadcrumbs = [];
        }
    }

    private function buildBreadcrumbs($nodeId)
    {
        $this->breadcrumbs = [];
        $node = ContentNode::find($nodeId);
        
        while ($node) {
            array_unshift($this->breadcrumbs, [
                'id' => $node->id,
                'name' => $node->name,
            ]);
            $node = $node->parent;
        }
    }

    public function render()
    {
        $contents = collect();
        $hasFolders = false;
        $hasFiles = false;

        if ($this->selectedYear) {
            $contents = ContentNode::where('scd_year_id', $this->selectedYear->id)
                ->where('category_group', 'content')
                ->where('parent_id', $this->currentParentId)
                ->orderBy('sequence')
                ->get();

            $hasFolders = $contents->where('type', 'folder')->isNotEmpty();
            $hasFiles = $contents->where('type', 'file')->isNotEmpty();
        }

        return view('livewire.backend.contents-index', [
            'contents' => $contents,
            'hasFolders' => $hasFolders,
            'hasFiles' => $hasFiles,
        ]);
    }
}
