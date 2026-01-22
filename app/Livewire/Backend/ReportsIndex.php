<?php

namespace App\Livewire\Backend;

use App\Models\ScdYear;
use App\Models\ScdReport;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.admin')]
class ReportsIndex extends Component
{
    use WithFileUploads;

    public $currentPage = 'reports';
    public $selectedYear;
    
    // Modal state
    public $showModal = false;
    public $editMode = false;
    public $reportId = null;
    
    // Form fields
    public $file_name;
    public $file;
    public $existingFile;

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

    public function openEditModal($reportId)
    {
        $this->resetForm();
        $report = ScdReport::findOrFail($reportId);
        
        $this->editMode = true;
        $this->reportId = $reportId;
        $this->file_name = $report->file_name;
        $this->existingFile = $report->file_path;
        
        $this->showModal = true;
    }

    public function saveReport()
    {
        // Validate
        $this->validate([
            'file_name' => 'required|string|max:255',
            'file' => $this->editMode ? 'nullable|mimes:pdf|max:10240' : 'required|mimes:pdf|max:10240',
        ]);

        if ($this->editMode) {
            $this->updateReport();
        } else {
            $this->createReport();
        }
    }

    private function createReport()
    {
        // Check if report already exists for this year
        $existingReport = ScdReport::where('scd_year_id', $this->selectedYear->id)->first();
        if ($existingReport) {
            $this->addError('file_name', 'รายงานสำหรับปีนี้มีอยู่แล้ว กรุณาแก้ไขรายงานที่มีอยู่');
            return;
        }

        $data = [
            'scd_year_id' => $this->selectedYear->id,
            'file_name' => $this->file_name,
        ];

        // Upload file
        if ($this->file) {
            $data['file_path'] = $this->file->store('reports', 'public');
        }

        ScdReport::create($data);

        $this->resetForm();
        $this->showModal = false;
    }

    private function updateReport()
    {
        $report = ScdReport::findOrFail($this->reportId);
        
        $data = [
            'file_name' => $this->file_name,
        ];

        // Upload new file
        if ($this->file) {
            // Delete old file
            if ($report->file_path) {
                Storage::disk('public')->delete($report->file_path);
            }
            $data['file_path'] = $this->file->store('reports', 'public');
        }

        $report->update($data);

        $this->resetForm();
        $this->showModal = false;
    }

    public function deleteReport($reportId)
    {
        $report = ScdReport::findOrFail($reportId);
        
        // Delete file from storage
        if ($report->file_path) {
            Storage::disk('public')->delete($report->file_path);
        }
        
        $report->delete();
    }

    private function resetForm()
    {
        $this->file_name = '';
        $this->file = null;
        $this->existingFile = null;
        $this->reportId = null;
        $this->resetValidation();
    }

    public function render()
    {
        $reports = $this->selectedYear 
            ? ScdReport::where('scd_year_id', $this->selectedYear->id)->get()
            : collect();

        return view('livewire.backend.reports-index', [
            'reports' => $reports
        ]);
    }
}
