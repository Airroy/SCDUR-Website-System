<?php

namespace App\Livewire\Backend;

use App\Models\ScdYear;
use App\Models\Banner;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.admin')]
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
    public $image;
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
        // Validate
        $this->validate([
            'sequence' => 'required|integer|min:1',
            'image' => $this->editMode ? 'nullable|image|max:2048' : 'required|image|max:2048',
            'link_type' => 'required|in:none,url,pdf',
            'link_url' => $this->link_type === 'url' ? 'required|url' : 'nullable',
            'pdf_file' => $this->link_type === 'pdf' && !$this->editMode ? 'required|mimes:pdf|max:10240' : 'nullable|mimes:pdf|max:10240',
            'pdf_name' => $this->link_type === 'pdf' ? 'required|string|max:255' : 'nullable',
        ]);

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

        // Upload image
        if ($this->image) {
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

        // Upload new image
        if ($this->image) {
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

    private function resetForm()
    {
        $this->reset([
            'bannerId',
            'sequence',
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
