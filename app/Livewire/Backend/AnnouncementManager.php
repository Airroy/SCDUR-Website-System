<?php

namespace App\Livewire\Backend;

use App\Models\ScdYear;
use App\Models\ContentNode;
use App\Services\ContentNodeService;
use Livewire\Component;
use Livewire\WithFileUploads;

class AnnouncementManager extends Component
{
    use WithFileUploads;

    public ScdYear $year;
    public $categoryGroup; // 'announcement' or 'order'
    public $nodes;
    public $currentNode = null; // For navigation
    public $breadcrumbs = [];
    
    public $showModal = false;
    public $modalType = ''; // 'folder' or 'file'
    public $editMode = false;
    
    // Form fields
    public $nodeId;
    public $name = '';
    public $sequence = '';
    public $pdf_file;
    public $existingFilePath = null;

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'sequence' => ['required', 'integer', 'min:1'],
        ];

        // Sequence uniqueness via service
        $rules['sequence'][] = function ($attribute, $value, $fail) {
            $service = app(ContentNodeService::class);
            $parentId = $this->currentNode?->id;
            $excludeId = $this->editMode ? $this->nodeId : null;
            
            if (!$service->isSequenceUnique($this->year, $value, $parentId, $excludeId, $this->categoryGroup)) {
                $fail('ลำดับนี้มีอยู่แล้ว');
            }
        };

        // PDF file rules for files only
        if ($this->modalType === 'file') {
            if (!$this->editMode || $this->pdf_file) {
                $rules['pdf_file'] = 'required|file|mimes:pdf|max:10240';
            }
        }

        return $rules;
    }

    protected $messages = [
        'name.required' => 'กรุณากรอกชื่อ',
        'name.max' => 'ชื่อต้องไม่เกิน 255 ตัวอักษร',
        'sequence.required' => 'กรุณากรอกลำดับ',
        'sequence.integer' => 'ลำดับต้องเป็นตัวเลข',
        'sequence.min' => 'ลำดับต้องมากกว่า 0',
        'pdf_file.required' => 'กรุณาเลือกไฟล์ PDF',
        'pdf_file.mimes' => 'ไฟล์ต้องเป็น PDF',
        'pdf_file.max' => 'ไฟล์มีขนาดใหญ่เกิน 10MB',
    ];

    public function mount(ScdYear $year, $categoryGroup)
    {
        $this->year = $year;
        $this->categoryGroup = $categoryGroup;
        $this->loadNodes();
        $this->updateBreadcrumbs();
    }

    public function loadNodes()
    {
        $service = app(ContentNodeService::class);
        $this->nodes = $service->getNodes($this->year, $this->currentNode?->id, $this->categoryGroup);
    }

    public function navigateTo($nodeId)
    {
        $this->currentNode = $nodeId ? ContentNode::findOrFail($nodeId) : null;
        $this->loadNodes();
        $this->updateBreadcrumbs();
    }

    public function updateBreadcrumbs()
    {
        $service = app(ContentNodeService::class);
        $this->breadcrumbs = $service->getBreadcrumbs($this->currentNode);
    }

    public function openFolderModal()
    {
        $this->openModal('folder');
    }

    public function openFileModal()
    {
        $this->openModal('file');
    }

    private function openModal(string $type)
    {
        $this->resetForm();
        $this->modalType = $type;
        $this->editMode = false;
        $this->showModal = true;
    }

    public function openEditModal($nodeId)
    {
        $node = ContentNode::findOrFail($nodeId);
        
        $this->nodeId = $node->id;
        $this->name = $node->name;
        $this->sequence = $node->sequence;
        $this->modalType = $node->type;
        $this->existingFilePath = $node->file_path;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        try {
            $service = app(ContentNodeService::class);
            
            $data = [
                'scd_year_id' => $this->year->id,
                'parent_id' => $this->currentNode?->id,
                'category_group' => $this->categoryGroup,
                'type' => $this->modalType,
                'name' => $this->name,
                'sequence' => $this->sequence,
            ];

            if ($this->editMode) {
                $node = ContentNode::findOrFail($this->nodeId);
                $service->update($node, $data, null, $this->pdf_file);
                $this->dispatch('notify', message: 'อัปเดตเรียบร้อยแล้ว', type: 'success');
            } else {
                $service->create($data, null, $this->pdf_file);
                $this->dispatch('notify', message: 'เพิ่มเรียบร้อยแล้ว', type: 'success');
            }

            $this->closeModal();
            $this->loadNodes();
            
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'เกิดข้อผิดพลาด: ' . $e->getMessage(), type: 'error');
        }
    }

    public function deleteNode($nodeId)
    {
        try {
            $service = app(ContentNodeService::class);
            $node = ContentNode::findOrFail($nodeId);
            $service->delete($node);
            
            $this->dispatch('notify', message: 'ลบเรียบร้อยแล้ว', type: 'success');
            $this->loadNodes();
            
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'เกิดข้อผิดพลาด: ' . $e->getMessage(), type: 'error');
        }
    }

    private function resetForm()
    {
        $this->nodeId = null;
        $this->name = '';
        $this->sequence = '';
        $this->pdf_file = null;
        $this->existingFilePath = null;
        $this->modalType = '';
    }

    public function render()
    {
        return view('livewire.backend.announcement-manager');
    }
}
