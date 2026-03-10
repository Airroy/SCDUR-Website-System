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

    public $showModal = false;
    public $showSortModal = false;
    public $sortableItems = [];
    public $sortCategory = 0;
    public $editMode = false;
    public $bannerId = null;

    public $category = 0;
    public $banner_image;
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
        $this->dispatch('updateTitle', 'รูปสไลด์ Banner' . ($this->selectedYear ? ' ' . $this->selectedYear->year : ''));
    }

    #[\Livewire\Attributes\On('cropped-image')]
    public function handleCroppedImage($name, $data)
    {
        if ($name === 'banner_image') {
            $this->banner_image = $data;
        }
    }

    public function openSortModal($category = 0)
    {
        if (!$this->selectedYear) return;

        $banners = Banner::where('scd_year_id', $this->selectedYear->id)
            ->where('category', $category)
            ->orderBy('sequence')
            ->orderBy('created_at', 'desc')
            ->get();

        $this->sortCategory = $category;
        $this->sortableItems = $banners->map(fn($b) => [
            'id'       => $b->id,
            'label'    => 'สไลด์ลำดับที่ ' . $b->sequence,
            'sublabel' => $b->link_type === 'none' ? 'ไม่มีลิงค์' : ($b->link_type === 'url' ? 'URL: ' . ($b->link_url ?? '-') : 'PDF'),
            'image'    => $b->image_path ? Storage::url($b->image_path) : null,
        ])->toArray();

        $this->showSortModal = true;
    }

    public function saveSortOrder($orderedIds)
    {
        foreach ($orderedIds as $index => $id) {
            $newSeq = $index + 1;
            Banner::where('id', $id)->where('sequence', '!=', $newSeq)->update(['sequence' => $newSeq]);
        }
        $this->showSortModal = false;
        $this->sortableItems = [];
        $this->dispatch('notify', ['message' => 'บันทึกลำดับสำเร็จ', 'type' => 'success']);
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
        $this->category = $banner->category;
        $this->existingImage = $banner->image_path;
        $this->link_type = $banner->link_type;
        $this->link_url = $banner->link_url;
        $this->pdf_name = $banner->pdf_name;
        $this->existingPdf = $banner->pdf_path;
        $this->showModal = true;
    }

    public function saveBanner()
    {
        $maxBannerSize = config('upload.max_file_sizes.banner', 5120);
        $maxPdfSize = config('upload.max_file_sizes.pdf', 10240);

        $rules = [
            'category' => 'required|integer|in:0,1',
            'banner_image' => 'nullable|string',
            'image' => "nullable|image|max:{$maxBannerSize}",
            'link_type' => 'required|in:none,url,pdf',
            'link_url' => $this->link_type === 'url' ? 'required|url' : 'nullable',
            'pdf_file' => $this->link_type === 'pdf' && !$this->editMode
                ? "required|mimes:pdf|max:{$maxPdfSize}"
                : "nullable|mimes:pdf|max:{$maxPdfSize}",
        ];

        $messages = [
            'image.max' => config('upload.messages.banner', 'รูป Banner ต้องมีขนาดไม่เกิน ' . ($maxBannerSize / 1024) . ' MB'),
            'pdf_file.max' => config('upload.messages.pdf', 'ไฟล์ PDF ต้องมีขนาดไม่เกิน ' . ($maxPdfSize / 1024) . ' MB'),
            'pdf_file.required' => 'กรุณาเลือกไฟล์ PDF',
            'pdf_file.mimes' => 'ไฟล์ต้องเป็น PDF เท่านั้น',
        ];

        if (!$this->editMode && !$this->banner_image && !$this->image) {
            $this->addError('image', config('upload.messages.image_required', 'กรุณาเลือกรูปภาพ'));
            return;
        }

        $this->validate($rules, $messages);

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
            'category' => $this->category,
            'link_type' => $this->link_type,
            'link_url' => $this->link_type === 'url' ? $this->link_url : null,
            'sequence' => Banner::where('scd_year_id', $this->selectedYear->id)
                ->where('category', $this->category)
                ->max('sequence') + 1,
        ];

        if ($this->banner_image) {
            $data['image_path'] = $this->saveBase64Image($this->banner_image, 'banners');
        } elseif ($this->image) {
            $data['image_path'] = $this->image->store('banners', 'public');
        }

        if ($this->link_type === 'pdf' && $this->pdf_file) {
            $originalName = $this->pdf_file->getClientOriginalName();
            $data['pdf_name'] = pathinfo($originalName, PATHINFO_FILENAME);
            $data['pdf_path'] = $this->pdf_file->storeAs('banners/pdfs', $originalName, 'public');
        }

        Banner::create($data);
        $this->selectedYear->touch();
        $this->showModal = false;
        $this->dispatch('notify', ['message' => 'เพิ่มรูปภาพสไลด์สำเร็จ', 'type' => 'success']);
    }

    private function updateBanner()
    {
        $banner = Banner::findOrFail($this->bannerId);

        $data = [
            'category' => $this->category,
            'link_type' => $this->link_type,
            'link_url' => $this->link_type === 'url' ? $this->link_url : null,
        ];

        if ($this->banner_image) {
            if ($banner->image_path) Storage::disk('public')->delete($banner->image_path);
            $data['image_path'] = $this->saveBase64Image($this->banner_image, 'banners');
        } elseif ($this->image) {
            if ($banner->image_path) Storage::disk('public')->delete($banner->image_path);
            $data['image_path'] = $this->image->store('banners', 'public');
        }

        if ($this->link_type === 'pdf' && $this->pdf_file) {
            if ($banner->pdf_path) Storage::disk('public')->delete($banner->pdf_path);
            $originalName = $this->pdf_file->getClientOriginalName();
            $data['pdf_name'] = pathinfo($originalName, PATHINFO_FILENAME);
            $data['pdf_path'] = $this->pdf_file->storeAs('banners/pdfs', $originalName, 'public');
        } elseif ($this->link_type !== 'pdf' && $banner->pdf_path) {
            Storage::disk('public')->delete($banner->pdf_path);
            $data['pdf_path'] = null;
            $data['pdf_name'] = null;
        }

        $oldCategory = $banner->category;
        $banner->update($data);
        $this->selectedYear->touch();

        if ((int)$oldCategory !== (int)$this->category) {
            $remaining = Banner::where('scd_year_id', $this->selectedYear->id)
                ->where('category', $oldCategory)
                ->orderBy('sequence')
                ->get();
            foreach ($remaining as $index => $item) {
                $item->update(['sequence' => $index + 1]);
            }
            $newMax = Banner::where('scd_year_id', $this->selectedYear->id)
                ->where('category', $this->category)
                ->where('id', '!=', $banner->id)
                ->max('sequence') ?? 0;
            $banner->update(['sequence' => $newMax + 1]);
        }

        $this->showModal = false;
        $this->dispatch('notify', ['message' => 'แก้ไขรูปภาพสไลด์สำเร็จ', 'type' => 'success']);
    }

    public function deleteBanner($bannerId)
    {
        $banner = Banner::findOrFail($bannerId);
        if ($banner->image_path) Storage::disk('public')->delete($banner->image_path);
        if ($banner->pdf_path) Storage::disk('public')->delete($banner->pdf_path);
        $banner->delete();
        $this->selectedYear->touch();
        $this->dispatch('notify', ['message' => 'ลบรูปภาพสไลด์สำเร็จ', 'type' => 'success']);
    }

    private function saveBase64Image($base64String, $folder)
    {
        if (empty($base64String)) return null;

        $extension = 'png';
        if (strpos($base64String, 'data:image/jpeg') === 0) $extension = 'jpg';
        elseif (strpos($base64String, 'data:image/png') === 0) $extension = 'png';

        $image = preg_replace('/^data:image\/\w+;base64,/', '', $base64String);
        $image = str_replace(' ', '+', $image);
        $imageName = uniqid() . '_' . time() . '.' . $extension;
        $path = $folder . '/' . $imageName;
        Storage::disk('public')->put($path, base64_decode($image));

        return $path;
    }

    private function resetForm()
    {
        $this->reset(['bannerId', 'category', 'banner_image', 'image', 'existingImage', 'link_type', 'link_url', 'pdf_file', 'pdf_name', 'existingPdf']);
        $this->link_type = 'none';
        $this->category = 0;
    }

    public function render()
    {
        $banners = $this->selectedYear
            ? Banner::where('scd_year_id', $this->selectedYear->id)
            ->orderBy('category')->orderBy('sequence')->orderBy('created_at', 'desc')->get()
            : collect([]);

        return view('livewire.backend.banners-index', ['banners' => $banners]);
    }
}