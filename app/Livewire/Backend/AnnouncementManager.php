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

    // Modal state
    public $showModal = false;
    public $editMode = false;
    public $nodeId = null;

    // Form fields
    public $sequence;
    public $name;
    public $type = 'folder'; // default to folder
    public $file;
    public $existingFile;

    protected $listeners = [
        'openEditAnnouncementModal' => 'openEditModal',
        'deleteAnnouncement' => 'deleteNode',
        'refreshAnnouncementTable' => '$refresh',
    ];

    /**
     * Get the model class based on categoryGroup
     */
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

        $this->showModal = true;
    }

    public function saveNode()
    {
        // ดึงค่า max file size จาก config
        $maxPdfSize = config('upload.max_file_sizes.pdf', 10240); // 10MB default

        $rules = [
            'sequence' => 'required|integer|min:1',
            'name' => 'required|string|max:255',
        ];

        // Custom messages
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

        // Check duplicate sequence
        $model = $this->getModel();
        $existingNode = $model::where('scd_year_id', $this->year->id)
            ->where('parent_id', $this->parentId)
            ->where('sequence', $this->sequence)
            ->when($this->editMode, fn($q) => $q->where('id', '!=', $this->nodeId))
            ->first();

        if ($existingNode) {
            $this->dispatch('notify', [
                'message' => 'ลำดับที่ ' . $this->sequence . ' มีอยู่แล้ว กรุณาเลือกลำดับอื่น',
                'type' => 'error'
            ]);
            return;
        }

        if ($this->editMode) {
            $this->updateNode();
        } else {
            $this->createNode();
        }
    }

    private function createNode()
    {
        $data = [
            'scd_year_id' => $this->year->id,
            'parent_id' => $this->parentId,
            'sequence' => $this->sequence,
            'name' => $this->name,
            'type' => $this->type,
        ];

        // Upload file (ใช้ชื่อไฟล์ต้นฉบับ)
        if ($this->type === 'file' && $this->file) {
            $folder = $this->categoryGroup === 'announcement' ? 'announcements' : 'orders';
            $originalName = $this->file->getClientOriginalName();
            $data['file_path'] = $this->file->storeAs($folder, $originalName, 'public');
        }

        $model = $this->getModel();
        $model::create($data);

        $this->showModal = false;
        $this->dispatch('notify', [
            'message' => $this->type === 'folder' ? 'เพิ่มหมวดหมู่สำเร็จ' : 'เพิ่มไฟล์สำเร็จ',
            'type' => 'success'
        ]);

        // Update local state
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

        $data = [
            'sequence' => $this->sequence,
            'name' => $this->name,
        ];

        // Upload new file (only for file type) - ใช้ชื่อไฟล์ต้นฉบับ
        if ($this->type === 'file' && $this->file) {
            // Delete old file
            if ($node->file_path) {
                Storage::disk('public')->delete($node->file_path);
            }
            $folder = $this->categoryGroup === 'announcement' ? 'announcements' : 'orders';
            $originalName = $this->file->getClientOriginalName();
            $data['file_path'] = $this->file->storeAs($folder, $originalName, 'public');
        }

        $node->update($data);

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
        $deletedType = $node->type;

        // Delete file
        if ($node->file_path) {
            Storage::disk('public')->delete($node->file_path);
        }

        // Delete children recursively
        $this->deleteChildren($node->id);

        $node->delete();

        // Recheck if there are still files/folders in this level
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
        ]);
        $this->type = 'folder';
    }

    public function render()
    {
        return view('livewire.backend.announcement-manager');
    }
}
