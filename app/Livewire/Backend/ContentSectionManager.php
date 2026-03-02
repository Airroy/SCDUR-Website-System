<?php

namespace App\Livewire\Backend;

use App\Models\ScdYear;
use App\Models\ContentSection;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ContentSectionManager extends Component
{
    use WithFileUploads;

    public $year;
    public $parentId = null;
    public $hasFilesInParent = false;
    public $hasFoldersInParent = false;

    // Modal state
    public $showModal = false;
    public $editMode = false;
    public $contentId = null;

    // Form fields
    public $sequence;
    public $name;
    public $type = 'folder'; // default to folder
    public $file;
    public $existingFile;
    public $image;
    public $existingImage;

    protected $listeners = [
        'openAddFolderModal',
        'openAddFileModal',
        'openEditContentModal' => 'openEditModal',
        'deleteContent' => 'deleteNode',
        'refreshContentTable' => '$refresh',
    ];

    public function mount($year, $parentId = null, $hasFilesInParent = false, $hasFoldersInParent = false)
    {
        $this->year = $year;
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

    public function openEditModal($contentId)
    {
        $this->resetForm();
        $node = ContentSection::findOrFail($contentId);

        $this->editMode = true;
        $this->contentId = $contentId;
        $this->sequence = $node->sequence;
        $this->name = $node->name;
        $this->type = $node->type;
        $this->existingFile = $node->file_path;
        $this->existingImage = $node->image_path;

        $this->showModal = true;
    }

    public function saveNode()
    {
        // ดึงค่า max file size จาก config
        $maxCoverSize = config('upload.max_file_sizes.cover', 3072); // 3MB default
        $maxPdfSize = config('upload.max_file_sizes.pdf', 10240); // 10MB default

        $rules = [
            'sequence' => 'required|integer|min:1',
            'name' => 'required|string|max:255',
        ];

        // Custom messages
        $messages = [
            'image.max' => config('upload.messages.cover', 'รูปปกต้องมีขนาดไม่เกิน ' . ($maxCoverSize / 1024) . ' MB'),
            'file.max' => config('upload.messages.pdf', 'ไฟล์ PDF ต้องมีขนาดไม่เกิน ' . ($maxPdfSize / 1024) . ' MB'),
            'file.required' => 'กรุณาเลือกไฟล์ PDF',
            'file.mimes' => 'ไฟล์ต้องเป็น PDF เท่านั้น',
            'image.required' => 'กรุณาเลือกรูปปก',
        ];

        if ($this->type === 'file') {
            if (!$this->editMode) {
                $rules['file'] = "required|file|mimes:pdf|max:{$maxPdfSize}";
            } else {
                $rules['file'] = "nullable|file|mimes:pdf|max:{$maxPdfSize}";
            }
        }

        // Image required for root level folders
        if ($this->type === 'folder' && $this->parentId === null) {
            if (!$this->editMode) {
                $rules['image'] = "required|image|max:{$maxCoverSize}";
            } else {
                $rules['image'] = "nullable|image|max:{$maxCoverSize}";
            }
        }

        $this->validate($rules, $messages);

        // Check duplicate sequence
        $existingNode = ContentSection::where('scd_year_id', $this->year->id)
            ->where('parent_id', $this->parentId)
            ->where('sequence', $this->sequence)
            ->when($this->editMode, fn($q) => $q->where('id', '!=', $this->contentId))
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
            $originalName = $this->file->getClientOriginalName();
            $data['file_path'] = $this->file->storeAs('contents', $originalName, 'public');
        }

        // Upload image for root level folders
        if ($this->type === 'folder' && $this->parentId === null && $this->image) {
            $data['image_path'] = $this->image->store('content-images', 'public');
        }

        ContentSection::create($data);

        $this->dispatch('upload-done'); // ✅ แจ้ง modal ว่าเสร็จแล้ว
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

        $this->dispatch('refreshContentTable');
    }

    private function updateNode()
    {
        $node = ContentSection::findOrFail($this->contentId);

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
            $originalName = $this->file->getClientOriginalName();
            $data['file_path'] = $this->file->storeAs('contents', $originalName, 'public');
        }

        // Upload new image (only for root level folders)
        if ($this->type === 'folder' && $this->parentId === null && $this->image) {
            // Delete old image
            if ($node->image_path) {
                Storage::disk('public')->delete($node->image_path);
            }
            $data['image_path'] = $this->image->store('content-images', 'public');
        }

        $node->update($data);

        $this->dispatch('upload-done'); // ✅ แจ้ง modal ว่าเสร็จแล้ว
        $this->showModal = false;
        $this->dispatch('notify', [
            'message' => 'แก้ไขรายการสำเร็จ',
            'type' => 'success'
        ]);
        $this->dispatch('refreshContentTable');
    }

    public function deleteNode($contentId)
    {
        $node = ContentSection::findOrFail($contentId);
        $deletedType = $node->type;

        // Delete file
        if ($node->file_path) {
            Storage::disk('public')->delete($node->file_path);
        }

        // Delete image
        if ($node->image_path) {
            Storage::disk('public')->delete($node->image_path);
        }

        // Delete children recursively
        $this->deleteChildren($node->id);

        $node->delete();

        // Recheck if there are still files/folders in this level
        $remainingFiles = ContentSection::where('scd_year_id', $this->year->id)
            ->where('parent_id', $this->parentId)
            ->where('type', 'file')
            ->exists();

        $remainingFolders = ContentSection::where('scd_year_id', $this->year->id)
            ->where('parent_id', $this->parentId)
            ->where('type', 'folder')
            ->exists();

        $this->hasFilesInParent = $remainingFiles;
        $this->hasFoldersInParent = $remainingFolders;

        $this->dispatch('notify', [
            'message' => 'ลบรายการสำเร็จ',
            'type' => 'success'
        ]);
        $this->dispatch('refreshContentTable');
    }

    private function deleteChildren($parentId)
    {
        $children = ContentSection::where('parent_id', $parentId)->get();
        foreach ($children as $child) {
            if ($child->file_path) {
                Storage::disk('public')->delete($child->file_path);
            }
            if ($child->image_path) {
                Storage::disk('public')->delete($child->image_path);
            }
            $this->deleteChildren($child->id);
            $child->delete();
        }
    }

    private function resetForm()
    {
        $this->reset([
            'contentId',
            'sequence',
            'name',
            'file',
            'existingFile',
            'image',
            'existingImage',
        ]);
        $this->type = 'folder';
    }

    public function render()
    {
        return view('livewire.backend.content-section-manager');
    }
}