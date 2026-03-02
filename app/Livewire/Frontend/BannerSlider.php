<?php

namespace App\Livewire\Frontend;

use App\Models\Banner;
use App\Models\ScdYear;
use Livewire\Component;

class BannerSlider extends Component
{
    public $yearId; // รับค่าจาก home.blade.php

    public function mount($yearId = null)
    {
        // ถ้าไม่ได้ส่ง yearId มา ให้ใช้ปีล่าสุด
        if (!$yearId) {
            $activeYear = ScdYear::where('is_published', true)
                ->latest('year')
                ->first();
            $this->yearId = $activeYear?->id;
        }
    }

    public function render()
    {
        // ดึง Banner ตามปีที่ได้รับ (เฉพาะหมวด 0 = แสดงผล, เรียงตามลำดับที่จัดไว้)
        $banners = $this->yearId
            ? Banner::where('scd_year_id', $this->yearId)
            ->where('category', 0)
            ->orderBy('sequence')
            ->get()
            : collect([]);

        return view('livewire.frontend.banner-slider', [
            'banners' => $banners
        ]);
    }
}
