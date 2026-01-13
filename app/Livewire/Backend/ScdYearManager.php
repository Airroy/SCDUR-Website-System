<?php

namespace App\Livewire\Backend;

use App\Models\ScdYear;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;

class ScdYearManager extends Component
{
    public $years;
    public $showModal = false;
    public $editMode = false;
    
    // Form fields
    public $yearId;
    public $year = '';
    public $created_date;
    public $is_published = false;
    
    protected function rules()
    {
        $rules = [
            'year' => ['required', 'digits:4', 'integer'],
            'created_date' => 'required|date',
            'is_published' => 'boolean',
        ];
        
        // Add unique validation, ignore current record when editing
        if ($this->editMode) {
            $rules['year'][] = 'unique:scd_years,year,' . $this->yearId;
        } else {
            $rules['year'][] = 'unique:scd_years,year';
        }
        
        return $rules;
    }
    
    protected $messages = [
        'year.required' => 'กรุณากรอกปี',
        'year.digits' => 'ปีต้องเป็นตัวเลข 4 หลัก',
        'year.integer' => 'ปีต้องเป็นตัวเลขเท่านั้น',
        'year.unique' => 'ปีนี้มีในระบบแล้ว',
        'created_date.required' => 'กรุณาเลือกวันที่สร้าง',
        'created_date.date' => 'รูปแบบวันที่ไม่ถูกต้อง',
    ];

    public function mount()
    {
        $this->loadYears();
        $this->created_date = now()->format('Y-m-d');
    }

    public function loadYears()
    {
        $this->years = ScdYear::with(['report', 'banners', 'contentNodes'])
            ->orderBy('created_date', 'desc')
            ->get();
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function openEditModal($yearId)
    {
        $year = ScdYear::findOrFail($yearId);
        
        $this->yearId = $year->id;
        $this->year = $year->year;
        $this->created_date = $year->created_date;
        $this->is_published = $year->is_published;
        
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
            if ($this->editMode) {
                $year = ScdYear::findOrFail($this->yearId);
                $year->update([
                    'year' => $this->year,
                    'created_date' => $this->created_date,
                    'is_published' => $this->is_published,
                ]);
                
                $this->dispatch('notify', message: 'อัปเดตข้อมูลปี SCD เรียบร้อยแล้ว', type: 'success');
            } else {
                ScdYear::create([
                    'year' => $this->year,
                    'created_date' => $this->created_date,
                    'is_published' => $this->is_published,
                ]);
                
                $this->dispatch('notify', message: 'เพิ่มปี SCD เรียบร้อยแล้ว', type: 'success');
            }

            $this->closeModal();
            $this->loadYears();
            
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'เกิดข้อผิดพลาด: ' . $e->getMessage(), type: 'error');
        }
    }

    public function togglePublish($yearId)
    {
        $year = ScdYear::findOrFail($yearId);
        $year->is_published = !$year->is_published;
        $year->save();
        
        $status = $year->is_published ? 'เปิดการแสดงผล' : 'ปิดการแสดงผล';
        $this->dispatch('notify', message: $status . 'ปี SCD ' . $year->year . ' เรียบร้อยแล้ว', type: 'success');
        
        $this->loadYears();
    }

    public function delete($yearId)
    {
        try {
            $year = ScdYear::findOrFail($yearId);
            $yearValue = $year->year;
            $year->delete();
            
            $this->dispatch('notify', message: 'ลบปี SCD ' . $yearValue . ' เรียบร้อยแล้ว', type: 'success');
            $this->loadYears();
            
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'เกิดข้อผิดพลาด: ' . $e->getMessage(), type: 'error');
        }
    }

    private function resetForm()
    {
        $this->yearId = null;
        $this->year = '';
        $this->created_date = now()->format('Y-m-d');
        $this->is_published = false;
    }

    public function render()
    {
        return view('livewire.backend.scd-year-manager');
    }
}
