<?php

namespace App\Livewire\Backend;

use App\Models\ScdYear;
use App\Models\ContentSection;
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

    public $showModal = false;
    public $showSortModal = false;
    public $sortableItems = [];
    public $editMode = false;
    public $type = 'folder';
    public $nodeId = null;

    public $name;
    public $image;
    public $file;
    public $existingImage = null;
    public $existingFile = null;
    public $is_hidden = false;

    protected $listeners = [
        'refreshContentTable' => '$refresh',
        'viewFolder'           => 'navigateToFolder',
        'openEditContentModal' => 'editNode',
        'deleteContent'        => 'deleteNode',
    ];

    public function mount($year = null, $folderId = null)
    {
        $this->selectedYear = $year ? ScdYear::where('year', $year)->first() : null;

        if ($folderId) {
            $this->currentParentId = $folderId;
            $this->buildBreadcrumbs($folderId);
        }

        $this->dispatch('updateTitle', 'ตัวชี้วัด Indicators' . ($this->selectedYear ? ' ' . $this->selectedYear->year : ''));
    }

    public function navigateToFolder($folderId)
    {
        $this->redirect(route('admin.contents.folder', [
            'year'     => $this->selectedYear->year,
            'folderId' => $folderId,
        ]));
    }

    public function navigateBack($parentId = null)
    {
        if ($parentId) {
            $this->redirect(route('admin.contents.folder', [
                'year'     => $this->selectedYear->year,
                'folderId' => $parentId,
            ]));
        } else {
            $this->redirect(route('admin.contents.index', [
                'year' => $this->selectedYear->year,
            ]));
        }
    }

    private function buildBreadcrumbs($nodeId)
    {
        $this->breadcrumbs = [];
        $node = ContentSection::find($nodeId);

        while ($node) {
            array_unshift($this->breadcrumbs, [
                'id'        => $node->id,
                'parent_id' => $node->parent_id,
                'name'      => $node->name,
            ]);
            $node = $node->parent;
        }
    }

    public function openSortModal()
    {
        if (!$this->selectedYear) return;

        $contents = ContentSection::where('scd_year_id', $this->selectedYear->id)
            ->where('parent_id', $this->currentParentId)
            ->where('is_hidden', false)
            ->orderBy('sequence')
            ->get();

        $this->sortableItems = $contents->map(fn($item) => [
            'id'       => $item->id,
            'label'    => $item->name,
            'sublabel' => $item->type === 'folder' ? 'หมวดหมู่' : 'ไฟล์',
            'image'    => $item->image_path ? Storage::url($item->image_path) : null,
        ])->toArray();

        $this->showSortModal = true;
    }

    public function saveSortOrder($orderedIds)
    {
        foreach ($orderedIds as $index => $id) {
            ContentSection::where('id', $id)->update(['sequence' => $index + 1]);
        }

        $this->selectedYear->touch();
        $this->showSortModal = false;
        $this->sortableItems = [];
        $this->dispatch('notify', ['message' => 'บันทึกลำดับสำเร็จ', 'type' => 'success']);
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

    public function editNode($contentId)
    {
        $node = ContentSection::findOrFail($contentId);
        $this->nodeId        = $node->id;
        $this->type          = $node->type;
        $this->name          = $node->name;
        $this->existingImage = $node->image_path;
        $this->existingFile  = $node->file_path;
        $this->is_hidden     = (bool) $node->is_hidden;
        $this->editMode      = true;
        $this->showModal     = true;
    }

    public function saveNode()
    {
        $rules = [
            'name' => 'required|string|max:255',
        ];

        if ($this->type === 'folder' && $this->currentParentId === null) {
            $rules['image'] = $this->editMode
                ? 'nullable|string'
                : 'required|string';
        }

        if ($this->type === 'file') {
            $maxPdfSize    = config('upload.max_file_sizes.pdf', 102400);
            $rules['file'] = $this->editMode
                ? "nullable|mimes:pdf|max:{$maxPdfSize}"
                : "required|mimes:pdf|max:{$maxPdfSize}";
        }

        $this->validate($rules, [
            'image.required' => 'กรุณาเลือกรูปภาพ',
            'file.required'  => 'กรุณาเลือกไฟล์ PDF',
        ]);

        if ($this->editMode) {
            $this->updateNode();
        } else {
            $this->createNode();
        }

        $this->showModal = false;
        $this->resetForm();
    }

    private function saveBase64Image(string $base64): string
    {
        $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $imageData = base64_decode($imageData);

        $filename = 'content-images/' . uniqid() . '.png';
        Storage::disk('public')->put($filename, $imageData);

        return $filename;
    }

    private function createNode()
    {
        $maxSequence = ContentSection::where('scd_year_id', $this->selectedYear->id)
            ->where('parent_id', $this->currentParentId)
            ->max('sequence') ?? 0;

        $data = [
            'scd_year_id' => $this->selectedYear->id,
            'parent_id'   => $this->currentParentId,
            'type'        => $this->type,
            'sequence'    => $maxSequence + 1,
            'name'        => $this->name,
            'is_hidden'   => $this->is_hidden,
        ];

        if ($this->image) {
            $data['image_path'] = $this->saveBase64Image($this->image);
        }

        if ($this->file) {
            $originalName      = $this->file->getClientOriginalName();
            $data['file_path'] = $this->file->storeAs('content-files', $originalName, 'public');
        }

        ContentSection::create($data);
        $this->selectedYear->touch();
        session()->flash('success', 'เพิ่มสำเร็จ');
    }

    private function updateNode()
    {
        $node = ContentSection::findOrFail($this->nodeId);
        $oldHidden = (bool) $node->is_hidden;

        $data = ['name' => $this->name, 'is_hidden' => $this->is_hidden];

        if ($this->image) {
            if ($this->existingImage) {
                Storage::disk('public')->delete($this->existingImage);
            }
            $data['image_path'] = $this->saveBase64Image($this->image);
        }

        if ($this->file) {
            if ($this->existingFile) {
                Storage::disk('public')->delete($this->existingFile);
            }
            $originalName      = $this->file->getClientOriginalName();
            $data['file_path'] = $this->file->storeAs('content-files', $originalName, 'public');
        }

        $node->update($data);
        $this->selectedYear->touch();

        if ($oldHidden !== (bool) $this->is_hidden) {
            $oldGroup = ContentSection::where('scd_year_id', $this->selectedYear->id)
                ->where('parent_id', $this->currentParentId)
                ->where('is_hidden', $oldHidden)
                ->orderBy('sequence')
                ->get();
            foreach ($oldGroup as $index => $item) {
                $item->update(['sequence' => $index + 1]);
            }
            $newMax = ContentSection::where('scd_year_id', $this->selectedYear->id)
                ->where('parent_id', $this->currentParentId)
                ->where('is_hidden', $this->is_hidden)
                ->where('id', '!=', $node->id)
                ->max('sequence') ?? 0;
            $node->update(['sequence' => $newMax + 1]);
        }

        session()->flash('success', 'อัพเดทสำเร็จ');
    }

    public function deleteNode($contentId)
    {
        $node = ContentSection::findOrFail($contentId);
        if ($node->image_path) Storage::disk('public')->delete($node->image_path);
        if ($node->file_path)  Storage::disk('public')->delete($node->file_path);
        $deletedHidden = (bool) $node->is_hidden;
        $node->delete();

        $remaining = ContentSection::where('scd_year_id', $this->selectedYear->id)
            ->where('parent_id', $this->currentParentId)
            ->where('is_hidden', $deletedHidden)
            ->orderBy('sequence')
            ->get();

        foreach ($remaining as $index => $item) {
            $item->update(['sequence' => $index + 1]);
        }

        $this->selectedYear->touch();
        session()->flash('success', 'ลบสำเร็จ');
    }

    private function resetForm()
    {
        $this->reset(['nodeId', 'name', 'image', 'file', 'existingImage', 'existingFile', 'is_hidden']);
        $this->editMode = false;
        $this->is_hidden = false;
    }

    public function render()
    {
        $contents = collect();
        $hasFolders = $hasFiles = $hasFoldersInParent = $hasFilesInParent = false;

        if ($this->selectedYear) {
            $contents = ContentSection::where('scd_year_id', $this->selectedYear->id)
                ->where('parent_id', $this->currentParentId)
                ->orderBy('is_hidden')
                ->orderBy('sequence')
                ->get();

            $hasFolders = $hasFoldersInParent = $contents->where('type', 'folder')->isNotEmpty();
            $hasFiles   = $hasFilesInParent   = $contents->where('type', 'file')->isNotEmpty();
        }

        return view('livewire.backend.contents-index', compact(
            'contents',
            'hasFolders',
            'hasFiles',
            'hasFoldersInParent',
            'hasFilesInParent'
        ));
    }
}