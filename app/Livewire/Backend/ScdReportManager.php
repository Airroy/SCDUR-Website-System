<?php

namespace App\Livewire\Backend;

use App\Models\ScdYear;
use App\Models\ScdReport;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ScdReportManager extends Component
{
    use WithFileUploads;

    public ScdYear $year;
    public $report;
    public $isEditing = false;
    
    // Form fields
    public $file_name = '';
    public $pdf_file;
    public $existingFilePath = null;

    protected $rules = [
        'file_name' => 'required|string|max:255',
        'pdf_file' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
    ];

    protected $messages = [
        'file_name.required' => 'กรุณากรอกชื่อไฟล์',
        'file_name.max' => 'ชื่อไฟล์ต้องไม่เกิน 255 ตัวอักษร',
        'pdf_file.file' => 'กรุณาเลือกไฟล์',
        'pdf_file.mimes' => 'ไฟล์ต้องเป็นประเภท PDF เท่านั้น',
        'pdf_file.max' => 'ไฟล์มีขนาดใหญ่เกิน 10MB',
    ];

    public function mount(ScdYear $year)
    {
        $this->year = $year;
        $this->loadReport();
    }

    public function loadReport()
    {
        $this->report = $this->year->report;
        
        if ($this->report) {
            $this->file_name = $this->report->file_name;
            $this->existingFilePath = $this->report->file_path;
        }
    }

    public function save()
    {
        // Validate file_name always
        $this->validate([
            'file_name' => 'required|string|max:255',
        ]);

        // If creating new report, PDF is required
        if (!$this->report && !$this->pdf_file) {
            $this->addError('pdf_file', 'กรุณาเลือกไฟล์ PDF');
            return;
        }

        // If updating and new file is uploaded, validate it
        if ($this->pdf_file) {
            $this->validate([
                'pdf_file' => 'file|mimes:pdf|max:10240',
            ]);
        }

        try {
            $filePath = $this->existingFilePath;

            // Handle file upload if new file provided
            if ($this->pdf_file) {
                // Delete old file if exists
                if ($this->report && $this->report->file_path) {
                    Storage::disk('public')->delete($this->report->file_path);
                }

                // Store new file
                $filePath = $this->pdf_file->store('reports', 'public');
            }

            // Create or update report
            if ($this->report) {
                $this->report->update([
                    'file_name' => $this->file_name,
                    'file_path' => $filePath,
                ]);
                
                $this->dispatch('notify', message: 'อัปเดตรายงานผลเรียบร้อยแล้ว', type: 'success');
            } else {
                ScdReport::create([
                    'scd_year_id' => $this->year->id,
                    'file_name' => $this->file_name,
                    'file_path' => $filePath,
                ]);
                
                $this->dispatch('notify', message: 'เพิ่มรายงานผลเรียบร้อยแล้ว', type: 'success');
            }

            $this->loadReport();
            $this->isEditing = false;
            $this->pdf_file = null;
            
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'เกิดข้อผิดพลาด: ' . $e->getMessage(), type: 'error');
        }
    }

    public function deleteReport()
    {
        if (!$this->report) {
            return;
        }

        try {
            // Delete file from storage
            if ($this->report->file_path) {
                Storage::disk('public')->delete($this->report->file_path);
            }

            $this->report->delete();
            
            $this->dispatch('notify', message: 'ลบรายงานผลเรียบร้อยแล้ว', type: 'success');
            $this->loadReport();
            $this->resetForm();
            
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'เกิดข้อผิดพลาด: ' . $e->getMessage(), type: 'error');
        }
    }

    public function resetForm()
    {
        $this->isEditing = false;
        $this->file_name = '';
        $this->pdf_file = null;
        $this->existingFilePath = null;
        
        if ($this->report) {
            $this->file_name = $this->report->file_name;
            $this->existingFilePath = $this->report->file_path;
        }
    }
    
    public function startEdit()
    {
        $this->isEditing = true;
        if ($this->report) {
            $this->file_name = $this->report->file_name;
            $this->existingFilePath = $this->report->file_path;
        }
    }
    
    public function cancelEdit()
    {
        $this->isEditing = false;
        $this->pdf_file = null;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.backend.scd-report-manager');
    }
}
