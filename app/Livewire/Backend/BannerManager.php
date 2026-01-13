<?php

namespace App\Livewire\Backend;

use App\Models\ScdYear;
use App\Models\Banner;
use App\Services\BannerService;
use Livewire\Component;
use Livewire\WithFileUploads;

class BannerManager extends Component
{
    use WithFileUploads;

    public ScdYear $year;
    public $banners;
    public $showModal = false;
    public $editMode = false;
    
    // Form fields
    public $bannerId;
    public $sequence = '';
    public $image;
    public $existingImagePath = null;
    public $link_type = 'none';
    public $link_url = '';
    public $pdf_name = '';
    public $pdf_file;
    public $existingPdfPath = null;

    protected function rules()
    {
        $rules = [
            'sequence' => ['required', 'integer', 'min:1'],
            'image' => $this->editMode && $this->existingImagePath ? 'nullable|image|max:5120' : 'required|image|max:5120',
            'link_type' => 'required|in:none,url,pdf',
        ];

        // Sequence uniqueness check via service
        $rules['sequence'][] = function ($attribute, $value, $fail) {
            $service = app(BannerService::class);
            $excludeId = $this->editMode ? $this->bannerId : null;
            
            if (!$service->isSequenceUnique($this->year, $value, $excludeId)) {
                $fail('ลำดับนี้มีอยู่แล้ว กรุณาเลือกลำดับอื่น');
            }
        };

        // Link type specific rules
        if ($this->link_type === 'url') {
            $rules['link_url'] = 'nullable|url';
        } elseif ($this->link_type === 'pdf') {
            $rules['pdf_name'] = 'nullable|string|max:255';
            if (!$this->editMode || $this->pdf_file) {
                $rules['pdf_file'] = 'nullable|file|mimes:pdf|max:10240';
            }
        }

        return $rules;
    }

    protected $messages = [
        'sequence.required' => 'กรุณากรอกลำดับ',
        'sequence.integer' => 'ลำดับต้องเป็นตัวเลข',
        'sequence.min' => 'ลำดับต้องมากกว่า 0',
        'image.required' => 'กรุณาเลือกรูปภาพ',
        'image.image' => 'ไฟล์ต้องเป็นรูปภาพ',
        'image.max' => 'รูปภาพมีขนาดใหญ่เกิน 5MB',
        'link_type.required' => 'กรุณาเลือกประเภท',
        'link_url.url' => 'รูปแบบ URL ไม่ถูกต้อง',
        'pdf_file.mimes' => 'ไฟล์ต้องเป็น PDF',
        'pdf_file.max' => 'ไฟล์มีขนาดใหญ่เกิน 10MB',
    ];

    public function mount(ScdYear $year)
    {
        $this->year = $year;
        $this->loadBanners();
    }

    public function loadBanners()
    {
        $service = app(BannerService::class);
        $this->banners = $service->getBanners($this->year);
    }

    public function openModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function openEditModal($bannerId)
    {
        $banner = Banner::findOrFail($bannerId);
        
        $this->bannerId = $banner->id;
        $this->sequence = $banner->sequence;
        $this->existingImagePath = $banner->image_path;
        $this->link_type = $banner->link_type;
        $this->link_url = $banner->link_url ?? '';
        $this->pdf_name = $banner->pdf_name ?? '';
        $this->existingPdfPath = $banner->pdf_path;
        
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
            $service = app(BannerService::class);
            
            $data = [
                'scd_year_id' => $this->year->id,
                'sequence' => $this->sequence,
                'image_path' => $this->existingImagePath,
                'link_type' => $this->link_type,
                'link_url' => $this->link_url,
                'pdf_name' => $this->pdf_name,
                'pdf_path' => $this->existingPdfPath,
            ];

            if ($this->editMode) {
                $banner = Banner::findOrFail($this->bannerId);
                $service->update($banner, $data, $this->image, $this->pdf_file);
                $this->dispatch('notify', message: 'อัปเดต Banner เรียบร้อยแล้ว', type: 'success');
            } else {
                $service->create($data, $this->image, $this->pdf_file);
                $this->dispatch('notify', message: 'เพิ่ม Banner เรียบร้อยแล้ว', type: 'success');
            }

            $this->closeModal();
            $this->loadBanners();
            
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'เกิดข้อผิดพลาด: ' . $e->getMessage(), type: 'error');
        }
    }

    public function deleteBanner($bannerId)
    {
        try {
            $service = app(BannerService::class);
            $banner = Banner::findOrFail($bannerId);
            $service->delete($banner);
            
            $this->dispatch('notify', message: 'ลบ Banner เรียบร้อยแล้ว', type: 'success');
            $this->loadBanners();
            
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'เกิดข้อผิดพลาด: ' . $e->getMessage(), type: 'error');
        }
    }

    private function resetForm()
    {
        $this->bannerId = null;
        $this->sequence = '';
        $this->image = null;
        $this->existingImagePath = null;
        $this->link_type = 'none';
        $this->link_url = '';
        $this->pdf_name = '';
        $this->pdf_file = null;
        $this->existingPdfPath = null;
    }

    public function render()
    {
        return view('livewire.backend.banner-manager');
    }
}
