<?php

namespace App\Livewire\Backend;

use App\Models\ScdYear;
use App\Models\Banner;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.admin')]
class BannersIndex extends Component
{
    use WithFileUploads;

    public $currentPage = 'banners';
    public $selectedYear;
    
    // Modal state
    public $showModal = false;
    public $editMode = false;
    public $bannerId = null;
    
    // Form fields
    public $sequence;
    public $banner_image; // รับ base64 จาก image-cropper
    public $image; // รับไฟล์ upload ปกติ
    public $existingImage;
    public $link_type = 'none';
    public $link_url;
    public $pdf_file;
    public $pdf_name;
    public $existingPdf;

    public function mount($year = null)
    {
        $this->selectedYear = $year ? ScdYear::where('year', $year)->first() : null;
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function openEditModal($bannerId)
    {
        $this->resetForm();
        $banner = Banner::findOrFail($bannerId);
        
        $this->editMode = true;
        $this->bannerId = $bannerId;
        $this->sequence = $banner->sequence;
        $this->existingImage = $banner->image_path;
        $this->link_type = $banner->link_type;
        $this->link_url = $banner->link_url;
        $this->pdf_name = $banner->pdf_name;
        $this->existingPdf = $banner->pdf_path;
        
        $this->showModal = true;
    }

    public function saveBanner()
    {
        // ดึงค่า max file size จาก config
        $maxBannerSize = config('upload.max_file_sizes.banner', 5120); // 5MB default
        $maxPdfSize = config('upload.max_file_sizes.pdf', 10240); // 10MB default

        // Validate
        $rules = [
            'sequence' => 'required|integer|min:1',
            'banner_image' => 'nullable|string', // base64
            'image' => $this->editMode 
                ? "nullable|image|max:{$maxBannerSize}" 
                : "nullable|image|max:{$maxBannerSize}",
            'link_type' => 'required|in:none,url,pdf',
            'link_url' => $this->link_type === 'url' ? 'required|url' : 'nullable',
            'pdf_file' => $this->link_type === 'pdf' && !$this->editMode 
                ? "required|mimes:pdf|max:{$maxPdfSize}" 
                : "nullable|mimes:pdf|max:{$maxPdfSize}",
            'pdf_name' => $this->link_type === 'pdf' ? 'required|string|max:255' : 'nullable',
        ];

        // Custom error messages
        $messages = [
            'image.max' => config('upload.messages.banner', 'รูป Banner ต้องมีขนาดไม่เกิน ' . ($maxBannerSize / 1024) . ' MB'),
            'pdf_file.max' => config('upload.messages.pdf', 'ไฟล์ PDF ต้องมีขนาดไม่เกิน ' . ($maxPdfSize / 1024) . ' MB'),
            'pdf_file.required' => 'กรุณาเลือกไฟล์ PDF',
            'pdf_file.mimes' => 'ไฟล์ต้องเป็น PDF เท่านั้น',
        ];

        // ตรวจสอบว่าต้องมีรูปภาพ (ถ้าไม่ใช่โหมดแก้ไข)
        if (!$this->editMode && !$this->banner_image && !$this->image) {
            $this->addError('image', config('upload.messages.image_required', 'กรุณาเลือกรูปภาพ'));
            return;
        }

        $this->validate($rules, $messages);

        // Check duplicate sequence
        $existingBanner = Banner::where('scd_year_id', $this->selectedYear->id)
            ->where('sequence', $this->sequence)
            ->when($this->editMode, fn($q) => $q->where('id', '!=', $this->bannerId))
            ->first();

        if ($existingBanner) {
            $this->dispatch('notify', [
                'message' => 'ลำดับที่ ' . $this->sequence . ' มีอยู่แล้ว กรุณาเลือกลำดับอื่น',
                'type' => 'error'
            ]);
            return;
        }

        if ($this->editMode) {
            $this->updateBanner();
        } else {
            $this->createBanner();
        }
    }

    private function createBanner()
    {
        $data = [
            'scd_year_id' => $this->selectedYear->id,
            'sequence' => $this->sequence,
            'link_type' => $this->link_type,
            'link_url' => $this->link_type === 'url' ? $this->link_url : null,
            'pdf_name' => $this->link_type === 'pdf' ? $this->pdf_name : null,
        ];

        // Upload image (รองรับทั้ง base64 และ file upload)
        if ($this->banner_image) {
            $data['image_path'] = $this->saveBase64Image($this->banner_image, 'banners');
        } elseif ($this->image) {
            $data['image_path'] = $this->image->store('banners', 'public');
        }

        // Upload PDF
        if ($this->link_type === 'pdf' && $this->pdf_file) {
            $data['pdf_path'] = $this->pdf_file->store('banners/pdfs', 'public');
        }

        Banner::create($data);

        $this->showModal = false;
        $this->dispatch('notify', [
            'message' => 'เพิ่ม Banner สำเร็จ',
            'type' => 'success'
        ]);
    }

    private function updateBanner()
    {
        $banner = Banner::findOrFail($this->bannerId);
        
        $data = [
            'sequence' => $this->sequence,
            'link_type' => $this->link_type,
            'link_url' => $this->link_type === 'url' ? $this->link_url : null,
            'pdf_name' => $this->link_type === 'pdf' ? $this->pdf_name : null,
        ];

        // Upload new image (รองรับทั้ง base64 และ file upload)
        if ($this->banner_image) {
            // Delete old image
            if ($banner->image_path) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $data['image_path'] = $this->saveBase64Image($this->banner_image, 'banners');
        } elseif ($this->image) {
            // Delete old image
            if ($banner->image_path) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $data['image_path'] = $this->image->store('banners', 'public');
        }

        // Upload new PDF
        if ($this->link_type === 'pdf' && $this->pdf_file) {
            // Delete old PDF
            if ($banner->pdf_path) {
                Storage::disk('public')->delete($banner->pdf_path);
            }
            $data['pdf_path'] = $this->pdf_file->store('banners/pdfs', 'public');
        } elseif ($this->link_type !== 'pdf' && $banner->pdf_path) {
            // Delete PDF if changed to other type
            Storage::disk('public')->delete($banner->pdf_path);
            $data['pdf_path'] = null;
            $data['pdf_name'] = null;
        }

        $banner->update($data);

        $this->showModal = false;
        $this->dispatch('notify', [
            'message' => 'แก้ไข Banner สำเร็จ',
            'type' => 'success'
        ]);
    }

    public function deleteBanner($bannerId)
    {
        $banner = Banner::findOrFail($bannerId);
        
        // Delete files
        if ($banner->image_path) {
            Storage::disk('public')->delete($banner->image_path);
        }
        if ($banner->pdf_path) {
            Storage::disk('public')->delete($banner->pdf_path);
        }
        
        $banner->delete();

        $this->dispatch('notify', [
            'message' => 'ลบ Banner สำเร็จ',
            'type' => 'success'
        ]);
    }

    /**
     * แปลง base64 เป็นไฟล์และบันทึก
     */
    private function saveBase64Image($base64String, $folder)
    {
        if (empty($base64String)) {
            return null;
        }

        // แยก header ออก (data:image/jpeg;base64,)
        $image = preg_replace('/^data:image\/\w+;base64,/', '', $base64String);
        $image = str_replace(' ', '+', $image);

        // สร้างชื่อไฟล์
        $imageName = uniqid() . '_' . time() . '.jpg';
        $path = $folder . '/' . $imageName;

        // บันทึกลง storage/app/public
        Storage::disk('public')->put($path, base64_decode($image));

        return $path;
    }

    private function resetForm()
    {
        $this->reset([
            'bannerId',
            'sequence',
            'banner_image',
            'image',
            'existingImage',
            'link_type',
            'link_url',
            'pdf_file',
            'pdf_name',
            'existingPdf'
        ]);
        $this->link_type = 'none';
    }

    public function render()
    {
        $banners = $this->selectedYear 
            ? Banner::where('scd_year_id', $this->selectedYear->id)
                ->orderBy('sequence')
                ->get()
            : collect([]);

        return view('livewire.backend.banners-index', [
            'banners' => $banners
        ]);
    }
}