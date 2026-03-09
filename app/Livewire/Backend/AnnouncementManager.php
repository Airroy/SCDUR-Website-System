<?php

namespace App\Livewire\Backend;

use App\Models\ScdYear;
use App\Models\Announcement;
use App\Models\Order;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class AnnouncementManager extends Component
{
    use WithFileUploads;

    public $year;
    public $categoryGroup;
    public $parentId = null;
    public $hasFilesInParent = false;
    public $hasFoldersInParent = false;

    public $showModal = false;
    public $editMode = false;
    public $nodeId = null;

    public $sequence;
    public $name;
    public $type = 'folder';
    public $file;
    public $existingFile;
    public $is_hidden = false;

    protected $listeners = [
        'openEditAnnouncementModal' => 'openEditModal',
        'deleteAnnouncement' => 'deleteNode',
        'refreshAnnouncementTable' => '$refresh',
    ];

    private function getModel(): string
    {
        return $this->categoryGroup === 'announcement' ? Announcement::class : Order::class;
    }

    public function mount($year, $categoryGroup, $parentId = null, $hasFilesInParent = false, $hasFoldersInParent = false)
    {
        $this->year = $year;
        $this->categoryGroup = $categoryGroup;
        $this->parentId = $parentId;
        $this->hasFilesInParent = $hasFilesInParent;
        $this->hasFoldersInParent = $hasFoldersInParent;
    }

    public function openAddFolderModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->type = 'folder';
        $this->showModal = true;
    }

    public function openAddFileModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->type = 'file';
        $this->showModal = true;
    }

    public function openEditModal($announcementId)
    {
        $this->resetForm();
        $model = $this->getModel();
        $node = $model::findOrFail($announcementId);

        $this->editMode = true;
        $this->nodeId = $announcementId;
        $this->sequence = $node->sequence;
        $this->name = $node->name;
        $this->type = $node->type;
        $this->existingFile = $node->file_path;
        $this->is_hidden = (bool) $node->is_hidden;

        $this->showModal = true;
    }

    public function saveNode()
    {
        $maxPdfSize = config('upload.max_file_sizes.pdf', 10240);

        $rules = [
            'name' => 'required|string|max:255',
        ];

        $messages = [
            'file.max' => config('upload.messages.pdf', 'ไฟล์ PDF ต้องมีขนาดไม่เกิน ' . ($maxPdfSize / 1024) . ' MB'),
            'file.required' => 'กรุณาเลือกไฟล์ PDF',
            'file.mimes' => 'ไฟล์ต้องเป็น PDF เท่านั้น',
        ];

        if ($this->type === 'file') {
            if (!$this->editMode) {
                $rules['file'] = "required|file|mimes:pdf|max:{$maxPdfSize}";
            } else {
                $rules['file'] = "nullable|file|mimes:pdf|max:{$maxPdfSize}";
            }
        }

        $this->validate($rules, $messages);

        if ($this->editMode) {
            $this->updateNode();
        } else {
            $this->createNode();
        }
    }

    private function createNode()
    {
        $model = $this->getModel();

        $nextSequence = ($model::where('scd_year_id', $this->year->id)
            ->where('parent_id', $this->parentId)
            ->where('is_hidden', $this->is_hidden)
            ->max('sequence') ?? 0) + 1;

        $data = [
            'scd_year_id' => $this->year->id,
            'parent_id' => $this->parentId,
            'sequence' => $nextSequence,
            'name' => $this->name,
            'type' => $this->type,
            'is_hidden' => $this->is_hidden,
        ];

        if ($this->type === 'file' && $this->file) {
            $folder = $this->categoryGroup === 'announcement' ? 'announcements' : 'orders';
            $originalName = $this->file->getClientOriginalName();
            $data['file_path'] = $this->file->storeAs($folder, $originalName, 'public');
        }

        $model::create($data);
        $this->year->touch();

        $this->showModal = false;
        $this->dispatch('notify', [
            'message' => $this->type === 'folder' ? 'เพิ่มหมวดหมู่สำเร็จ' : 'เพิ่มไฟล์สำเร็จ',
            'type' => 'success'
        ]);

        if ($this->type === 'folder') {
            $this->hasFoldersInParent = true;
        } else {
            $this->hasFilesInParent = true;
        }

        $this->dispatch('refreshAnnouncementTable');
    }

    private function updateNode()
    {
        $model = $this->getModel();
        $node = $model::findOrFail($this->nodeId);
        $oldHidden = (bool) $node->is_hidden;

        $data = [
            'name' => $this->name,
            'is_hidden' => $this->is_hidden,
        ];

        if ($this->type === 'file' && $this->file) {
            if ($node->file_path) {
                Storage::disk('public')->delete($node->file_path);
            }
            $folder = $this->categoryGroup === 'announcement' ? 'announcements' : 'orders';
            $originalName = $this->file->getClientOriginalName();
            $data['file_path'] = $this->file->storeAs($folder, $originalName, 'public');
        }

        $node->update($data);
        $this->year->touch();

        if ($oldHidden !== (bool) $this->is_hidden) {
            $oldGroup = $model::where('scd_year_id', $this->year->id)
                ->where('parent_id', $this->parentId)
                ->where('is_hidden', $oldHidden)
                ->orderBy('sequence')
                ->get();
            foreach ($oldGroup as $index => $item) {
                $item->update(['sequence' => $index + 1]);
            }
            $newMax = $model::where('scd_year_id', $this->year->id)
                ->where('parent_id', $this->parentId)
                ->where('is_hidden', $this->is_hidden)
                ->where('id', '!=', $node->id)
                ->max('sequence') ?? 0;
            $node->update(['sequence' => $newMax + 1]);
        }

        $this->showModal = false;
        $this->dispatch('notify', [
            'message' => 'แก้ไขรายการสำเร็จ',
            'type' => 'success'
        ]);
        $this->dispatch('refreshAnnouncementTable');
    }

    public function deleteNode($announcementId)
    {
        $model = $this->getModel();
        $node = $model::findOrFail($announcementId);

        if ($node->file_path) {
            Storage::disk('public')->delete($node->file_path);
        }

        $deletedHidden = (bool) $node->is_hidden;
        $this->deleteChildren($node->id);
        $node->delete();

        $remaining = $model::where('scd_year_id', $this->year->id)
            ->where('parent_id', $this->parentId)
            ->where('is_hidden', $deletedHidden)
            ->orderBy('sequence')
            ->get();
        foreach ($remaining as $index => $item) {
            $item->update(['sequence' => $index + 1]);
        }

        $this->year->touch();

        $remainingFiles = $model::where('scd_year_id', $this->year->id)
            ->where('parent_id', $this->parentId)
            ->where('type', 'file')
            ->exists();

        $remainingFolders = $model::where('scd_year_id', $this->year->id)
            ->where('parent_id', $this->parentId)
            ->where('type', 'folder')
            ->exists();

        $this->hasFilesInParent = $remainingFiles;
        $this->hasFoldersInParent = $remainingFolders;

        $this->dispatch('notify', [
            'message' => 'ลบรายการสำเร็จ',
            'type' => 'success'
        ]);
        $this->dispatch('refreshAnnouncementTable');
    }

    private function deleteChildren($parentId)
    {
        $model = $this->getModel();
        $children = $model::where('parent_id', $parentId)->get();
        foreach ($children as $child) {
            if ($child->file_path) {
                Storage::disk('public')->delete($child->file_path);
            }
            $this->deleteChildren($child->id);
            $child->delete();
        }
    }

    private function resetForm()
    {
        $this->reset([
            'nodeId',
            'sequence',
            'name',
            'file',
            'existingFile',
            'is_hidden',
        ]);
        $this->type = 'folder';
        $this->is_hidden = false;
    }

    public function render()
    {
        return view('livewire.backend.announcement-manager');
    }
}