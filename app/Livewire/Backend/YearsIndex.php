<?php

namespace App\Livewire\Backend;

use App\Models\ScdYear;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class YearsIndex extends Component
{
    public $showCreateModal = false;
    public $showEditModal = false;
    public $year;
    public $created_date;
    public $is_published = false;
    public $editingYearId = null;

    protected $rules = [
        'year' => 'required|integer|min:1900|max:2200|unique:scd_years,year',
        'created_date' => 'required|date',
        'is_published' => 'boolean',
    ];

    public function createYear()
    {
        $this->validate();

        ScdYear::create([
            'year' => $this->year,
            'created_date' => $this->created_date,
            'is_published' => $this->is_published,
        ]);

        $this->showCreateModal = false;
        $this->reset(['year', 'created_date', 'is_published']);
        session()->flash('message', 'เพิ่มปีใหม่เรียบร้อยแล้ว');
    }

    public function editYear($id)
    {
        $yearModel = ScdYear::findOrFail($id);
        $this->editingYearId = $yearModel->id;
        $this->year = $yearModel->year;
        $this->created_date = $yearModel->created_date->format('Y-m-d');
        $this->is_published = $yearModel->is_published;
        $this->showEditModal = true;
    }

    public function updateYear()
    {
        $this->validate([
            'year' => 'required|integer|min:1900|max:2200|unique:scd_years,year,' . $this->editingYearId,
            'created_date' => 'required|date',
            'is_published' => 'boolean',
        ]);

        $yearModel = ScdYear::findOrFail($this->editingYearId);
        $yearModel->update([
            'year' => $this->year,
            'created_date' => $this->created_date,
            'is_published' => $this->is_published,
        ]);

        $this->showEditModal = false;
        $this->reset(['year', 'created_date', 'is_published', 'editingYearId']);
        session()->flash('message', 'อัพเดทข้อมูลเรียบร้อยแล้ว');
    }

    public function deleteYear($id)
    {
        $yearModel = ScdYear::findOrFail($id);
        $yearModel->delete();
        session()->flash('message', 'ลบข้อมูลเรียบร้อยแล้ว');
    }

    public function togglePublish($id)
    {
        $yearModel = ScdYear::findOrFail($id);
        $yearModel->update(['is_published' => !$yearModel->is_published]);
    }

    public function render()
    {
        $years = ScdYear::orderBy('year', 'desc')->get();
        
        return view('livewire.backend.years-index', compact('years'));
    }
}
