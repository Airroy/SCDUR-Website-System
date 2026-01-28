<?php

namespace App\Livewire\Backend;

use App\Models\ScdYear;
use App\Models\ContentNode;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.admin')]
class ContentsIndex extends Component
{
    use WithFileUploads;

    public $currentPage = 'contents';
    public $selectedYear;
    public $currentParentId = null;
    public $breadcrumbs = [];
    
    // Modal & Form Properties
    public $showModal = false;
    public $editMode = false;
    public $type = 'folder'; // 'folder' or 'file'
    public $nodeId = null;
    
    // Form Fields
    public $sequence;
    public $name;
    public $image;
    public $file;
    public $existingImage = null;
    public $existingFile = null;

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

    public function openAddFolderModal()
    {
        $this->resetForm();
        $this->type = 'folder';
        $this->showModal = true;
    }

    public function openAddFileModal()
    {
        $this->resetForm();
        $this->type = 'file';
        $this->showModal = true;
    }

    public function editNode($nodeId)
    {
        $node = ContentNode::findOrFail($nodeId);
        
        $this->nodeId = $node->id;
        $this->type = $node->type;
        $this->sequence = $node->sequence;
        $this->name = $node->name;
        $this->existingImage = $node->image_path;
        $this->existingFile = $node->file_path;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function saveNode()
    {
        $rules = [
            'sequence' => 'required|integer|min:1',
            'name' => 'required|string|max:255',
        ];

        // Add validation for image (required only for root folders)
        if ($this->type === 'folder' && $this->currentParentId === null) {
            if (!$this->editMode) {
                $rules['image'] = 'required|image|max:2048';
            } else {
                $rules['image'] = 'nullable|image|max:2048';
            }
        }

        // Add validation for file
        if ($this->type === 'file') {
            if (!$this->editMode) {
                $rules['file'] = 'required|mimes:pdf|max:10240';
            } else {
                $rules['file'] = 'nullable|mimes:pdf|max:10240';
            }
        }

        $this->validate($rules);

        $data = [
            'scd_year_id' => $this->selectedYear->id,
            'category_group' => 'content',
            'parent_id' => $this->currentParentId,
            'type' => $this->type,
            'sequence' => $this->sequence,
            'name' => $this->name,
        ];

        // Handle image upload
        if ($this->image) {
            if ($this->existingImage) {
                Storage::disk('public')->delete($this->existingImage);
            }
            $data['image_path'] = $this->image->store('content-images', 'public');
        }

        // Handle file upload
        if ($this->file) {
            if ($this->existingFile) {
                Storage::disk('public')->delete($this->existingFile);
            }
            $data['file_path'] = $this->file->store('content-files', 'public');
        }

        if ($this->editMode) {
            $node = ContentNode::findOrFail($this->nodeId);
            $node->update($data);
            session()->flash('success', 'อัพเดทสำเร็จ');
        } else {
            ContentNode::create($data);
            session()->flash('success', 'เพิ่มสำเร็จ');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function deleteNode($nodeId)
    {
        $node = ContentNode::findOrFail($nodeId);
        
        // Delete associated files
        if ($node->image_path) {
            Storage::disk('public')->delete($node->image_path);
        }
        if ($node->file_path) {
            Storage::disk('public')->delete($node->file_path);
        }
        
        $node->delete();
        session()->flash('success', 'ลบสำเร็จ');
    }

    private function resetForm()
    {
        $this->reset(['nodeId', 'sequence', 'name', 'image', 'file', 'existingImage', 'existingFile']);
        $this->editMode = false;
    }

    public function render()
    {
        $contents = collect();
        $hasFolders = false;
        $hasFiles = false;
        $hasFoldersInParent = false;
        $hasFilesInParent = false;

        if ($this->selectedYear) {
            $contents = ContentNode::where('scd_year_id', $this->selectedYear->id)
                ->where('category_group', 'content')
                ->where('parent_id', $this->currentParentId)
                ->orderBy('sequence')
                ->get();

            $hasFolders = $contents->where('type', 'folder')->isNotEmpty();
            $hasFiles = $contents->where('type', 'file')->isNotEmpty();
            $hasFoldersInParent = $hasFolders;
            $hasFilesInParent = $hasFiles;
        }

        return view('livewire.backend.contents-index', [
            'contents' => $contents,
            'hasFolders' => $hasFolders,
            'hasFiles' => $hasFiles,
            'hasFoldersInParent' => $hasFoldersInParent,
            'hasFilesInParent' => $hasFilesInParent,
        ]);
    }
}