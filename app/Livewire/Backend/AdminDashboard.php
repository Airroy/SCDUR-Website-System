<?php

namespace App\Livewire\Backend;

use App\Models\ScdYear;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class AdminDashboard extends Component
{
    public function mount()
    {
        $this->dispatch('updateTitle', 'หน้าหลัก');
    }

    public function render()
    {
        $stats = [
            'total_years' => ScdYear::count(),
            'published_years' => ScdYear::where('is_published', true)->count(),
            'total_reports' => \App\Models\ScdReport::count(),
            'total_banners' => \App\Models\Banner::count(),
        ];

        return view('livewire.backend.admin-dashboard', compact('stats'));
    }
}
