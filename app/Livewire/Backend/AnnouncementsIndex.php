<?php

namespace App\Livewire\Backend;

use App\Models\ScdYear;
use App\Models\Announcement;
use App\Models\Order;
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

        // ตรวจสอบจาก route ว่าอยู่หน้าประกาศหรือคำสั่ง
        if (request()->routeIs('admin.directives.*')) {
            $this->category = 'order';
            $this->currentPage = 'directives';
        }
    }

    /**
     * Get the model class based on current category
     */
    private function getModel(): string
    {
        return $this->category === 'announcement' ? Announcement::class : Order::class;
    }

    public function switchCategory($category)
    {
        // Redirect ไป URL ที่ถูกต้อง
        $route = $category === 'announcement' ? 'admin.announcements.index' : 'admin.directives.index';
        $params = $this->selectedYear ? ['year' => $this->selectedYear->year] : [];
        return $this->redirect(route($route, $params), navigate: true);
    }

    public function navigateToFolder($folderId)
    {
        $model = $this->getModel();
        $folder = $model::find($folderId);
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
            $model = $this->getModel();
            $folder = $model::find($parentId);
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

        $model = $this->getModel();
        return $model::where('scd_year_id', $this->selectedYear->id)
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
            $model = $this->getModel();
            $items = $model::where('scd_year_id', $this->selectedYear->id)
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
