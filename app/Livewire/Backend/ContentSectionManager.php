<?php

namespace App\Livewire\Backend;

use App\Models\ScdYear;
use App\Models\ContentNode;
use App\Services\ContentNodeService;
use Livewire\Component;
use Livewire\WithFileUploads;

class ContentSectionManager extends Component
{
    use WithFileUploads;

    public ScdYear $year;
    public $nodes;
    public $currentNode = null;
    public $breadcrumbs = [];
    
    public $showModal = false;
    public $modalType = '';
    public $editMode = false;
    
    public $nodeId;
    public $name = '';
    public $sequence = '';
    public $image;
    public $existingImagePath = null;
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
            
            if (!$service->isSequenceUnique($this->year, $value, $parentId, $excludeId)) {
                $fail('ลำดับนี้มีอยู่แล้ว');
            }
        };

        if ($this->modalType === 'folder' && !$this->currentNode) {
            // เฉพาะหมวดหมู่ระดับรากเท่านั้นที่ต้องมีรูป
            $rules['image'] = ($this->editMode && $this->existingImagePath) 
                ? 'nullable|image|max:5120' 
                : 'required|image|max:5120';
        }

        if ($this->modalType === 'file') {
            if (!$this->editMode || $this->pdf_file) {
                $rules['pdf_file'] = 'required|file|mimes:pdf|max:10240';
            }
        }

        return $rules;
    }

    protected $messages = [
        'name.required' => 'กรุณากรอกชื่อหัวข้อ',
        'name.max' => 'ชื่อต้องไม่เกิน 255 ตัวอักษร',
        'sequence.required' => 'กรุณากรอกลำดับ',
        'sequence.integer' => 'ลำดับต้องเป็นตัวเลข',
        'sequence.min' => 'ลำดับต้องมากกว่า 0',
        'image.required' => 'กรุณาเลือกรูปภาพ',
        'image.image' => 'ไฟล์ต้องเป็นรูปภาพ',
        'image.max' => 'รูปภาพมีขนาดใหญ่เกิน 5MB',
        'pdf_file.required' => 'กรุณาเลือกไฟล์ PDF',
        'pdf_file.mimes' => 'ไฟล์ต้องเป็น PDF',
        'pdf_file.max' => 'ไฟล์มีขนาดใหญ่เกิน 10MB',
    ];

    public function mount(ScdYear $year)
    {
        $this->year = $year;
        $this->loadNodes();
        $this->updateBreadcrumbs();
    }

    public function loadNodes()
    {
        $service = app(ContentNodeService::class);
        $this->nodes = $service->getNodes($this->year, $this->currentNode?->id);
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
        $this->existingImagePath = $node->image_path;
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
                'category_group' => 'content_section',
                'type' => $this->modalType,
                'name' => $this->name,
                'sequence' => $this->sequence,
            ];

            if ($this->editMode) {
                $node = ContentNode::findOrFail($this->nodeId);
                $service->update($node, $data, $this->image, $this->pdf_file);
                $this->dispatch('notify', message: 'อัปเดตเรียบร้อยแล้ว', type: 'success');
            } else {
                $service->create($data, $this->image, $this->pdf_file);
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
        $this->image = null;
        $this->existingImagePath = null;
        $this->pdf_file = null;
        $this->existingFilePath = null;
        $this->modalType = '';
    }

    public function render()
    {
        return view('livewire.backend.content-section-manager');
    }
}
