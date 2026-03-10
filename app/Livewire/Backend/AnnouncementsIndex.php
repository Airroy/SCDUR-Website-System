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
    public $currentPage = 'announcements';

    public $showSortModal = false;
    public $sortableItems = [];

    protected $listeners = [
        'refreshAnnouncementTable' => '$refresh',
        'navigateToFolder' => 'navigateToFolder',
    ];

    public function mount($year = null, $folderId = null)
    {
        $this->selectedYear = $year ? ScdYear::where('year', $year)->first() : null;

        // ตรวจสอบว่าเป็น directives หรือ announcements ก่อน
        // เพราะต้องใช้ getModel() ใน buildBreadcrumbs
        if (request()->routeIs('admin.directives.*')) {
            $this->category = 'order';
            $this->currentPage = 'directives';
            $this->dispatch('updateTitle', 'คำสั่ง' . ($this->selectedYear ? ' ' . $this->selectedYear->year : ''));
        } else {
            $this->dispatch('updateTitle', 'ประกาศ' . ($this->selectedYear ? ' ' . $this->selectedYear->year : ''));
        }

        // รับ folderId จาก URL แล้ว build state
        if ($folderId) {
            $this->currentParentId = $folderId;
            $model = $this->getModel();
            $folder = $model::find($folderId);
            if ($folder) {
                $this->buildBreadcrumbs($folder);
            }
        }
    }

    private function getModel(): string
    {
        return $this->category === 'announcement' ? Announcement::class : Order::class;
    }

    public function switchCategory($category)
    {
        $route = $category === 'announcement' ? 'admin.announcements.index' : 'admin.directives.index';
        $params = $this->selectedYear ? ['year' => $this->selectedYear->year] : [];
        return $this->redirect(route($route, $params), navigate: true);
    }

    public function navigateToFolder($folderId)
    {
        $routeName = $this->category === 'announcement'
            ? 'admin.announcements.folder'
            : 'admin.directives.folder';

        $this->redirect(route($routeName, [
            'year' => $this->selectedYear->year,
            'folderId' => $folderId,
        ]));
    }

    public function navigateBack($parentId = null)
    {
        if ($parentId === null) {
            // กลับไปหน้าหลักของปีนั้น
            $routeName = $this->category === 'announcement'
                ? 'admin.announcements.index'
                : 'admin.directives.index';

            $this->redirect(route($routeName, [
                'year' => $this->selectedYear->year,
            ]));
        } else {
            // กลับไป folder ก่อนหน้า
            $routeName = $this->category === 'announcement'
                ? 'admin.announcements.folder'
                : 'admin.directives.folder';

            $this->redirect(route($routeName, [
                'year' => $this->selectedYear->year,
                'folderId' => $parentId,
            ]));
        }
    }

    private function buildBreadcrumbs($folder)
    {
        $this->breadcrumbs = [];
        $current = $folder;
        while ($current) {
            array_unshift($this->breadcrumbs, [
                'id' => $current->id,
                'parent_id' => $current->parent_id,
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

    public function openSortModal()
    {
        if (!$this->selectedYear) return;
        $model = $this->getModel();
        $items = $model::where('scd_year_id', $this->selectedYear->id)
            ->where('parent_id', $this->currentParentId)
            ->where('is_hidden', false)
            ->orderBy('sequence')
            ->get();

        $this->sortableItems = $items->map(fn($item) => [
            'id' => $item->id,
            'label' => $item->name,
            'sublabel' => $item->type === 'folder' ? 'หมวดหมู่' : 'ไฟล์ PDF',
        ])->toArray();

        $this->showSortModal = true;
    }

    public function saveSortOrder($orderedIds)
    {
        $model = $this->getModel();
        foreach ($orderedIds as $index => $id) {
            $newSeq = $index + 1;
            $model::where('id', $id)->where('sequence', '!=', $newSeq)->update(['sequence' => $newSeq]);
        }
        $this->showSortModal = false;
        $this->sortableItems = [];
        $this->dispatch('notify', ['message' => 'บันทึกลำดับสำเร็จ', 'type' => 'success']);
    }

    public function toggleHidden($nodeId)
    {
        $model = $this->getModel();
        $node = $model::findOrFail($nodeId);
        $oldHidden = (bool) $node->is_hidden;
        $newHidden = !$oldHidden;

        $node->update(['is_hidden' => $newHidden]);

        $oldGroup = $model::where('scd_year_id', $this->selectedYear->id)
            ->where('parent_id', $this->currentParentId)
            ->where('is_hidden', $oldHidden)
            ->where('id', '!=', $node->id)
            ->orderBy('sequence')
            ->get();
        $model::withoutTimestamps(function () use ($oldGroup) {
            foreach ($oldGroup as $index => $item) {
                $item->update(['sequence' => $index + 1]);
            }
        });

        $newMax = $model::where('scd_year_id', $this->selectedYear->id)
            ->where('parent_id', $this->currentParentId)
            ->where('is_hidden', $newHidden)
            ->where('id', '!=', $node->id)
            ->max('sequence') ?? 0;
        $node->withoutTimestamps(fn() => $node->update(['sequence' => $newMax + 1]));
    }

    public function render()
    {
        $items = collect();
        $hasFiles = $hasFolders = false;

        if ($this->selectedYear) {
            $model = $this->getModel();
            $items = $model::where('scd_year_id', $this->selectedYear->id)
                ->where('parent_id', $this->currentParentId)
                ->orderBy('is_hidden')
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
