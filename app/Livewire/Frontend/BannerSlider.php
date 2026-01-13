<?php

namespace App\Livewire\Frontend;

use App\Models\Banner;
use App\Models\ScdYear;
use Livewire\Component;

class BannerSlider extends Component
{
    public function render()
    {
        // ดึง Banner จากปีที่ publish แล้วเท่านั้น
        $activeYear = ScdYear::where('is_published', true)->latest('year')->first();
        
        $banners = $activeYear 
            ? Banner::where('scd_year_id', $activeYear->id)
                    ->orderBy('sequence')
                    ->get()
            : collect([]);

        return view('livewire.frontend.banner-slider', [
            'banners' => $banners
        ]);
    }
}
