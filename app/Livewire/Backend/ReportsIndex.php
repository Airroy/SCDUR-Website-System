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

    public $showModal = false;
    public $editMode = false;
    public $reportId = null;

    public $file_name;
    public $file;
    public $existingFile;

    public function mount($year = null)
    {
        $this->selectedYear = $year ? ScdYear::where('year', $year)->first() : null;
        $this->dispatch('updateTitle', 'รายงานผล SCD' . ($this->selectedYear ? ' ' . $this->selectedYear->year : ''));
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
        $maxPdfSize = config('upload.max_file_sizes.pdf', 102400);
        $this->validate([
            'file' => $this->editMode ? "nullable|mimes:pdf|max:{$maxPdfSize}" : "required|mimes:pdf|max:{$maxPdfSize}",
        ]);

        if ($this->editMode) {
            $this->updateReport();
        } else {
            $this->createReport();
        }
    }

    private function createReport()
    {
        $existingReport = ScdReport::where('scd_year_id', $this->selectedYear->id)->first();
        if ($existingReport) {
            $this->addError('file', 'รายงานสำหรับปีนี้มีอยู่แล้ว กรุณาแก้ไขรายงานที่มีอยู่');
            return;
        }

        $data = ['scd_year_id' => $this->selectedYear->id];

        if ($this->file) {
            $originalName = $this->file->getClientOriginalName();
            $data['file_name'] = pathinfo($originalName, PATHINFO_FILENAME);
            $data['file_path'] = $this->file->storeAs('reports', $originalName, 'public');
        }

        ScdReport::create($data);
        $this->resetForm();
        $this->showModal = false;
    }

    private function updateReport()
    {
        $report = ScdReport::findOrFail($this->reportId);
        $data = [];

        if ($this->file) {
            if ($report->file_path) Storage::disk('public')->delete($report->file_path);
            $originalName = $this->file->getClientOriginalName();
            $data['file_name'] = pathinfo($originalName, PATHINFO_FILENAME);
            $data['file_path'] = $this->file->storeAs('reports', $originalName, 'public');
        }

        $report->update($data);
        $this->resetForm();
        $this->showModal = false;
    }

    public function deleteReport($reportId)
    {
        $report = ScdReport::findOrFail($reportId);
        if ($report->file_path) Storage::disk('public')->delete($report->file_path);
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

        return view('livewire.backend.reports-index', ['reports' => $reports]);
    }
}
