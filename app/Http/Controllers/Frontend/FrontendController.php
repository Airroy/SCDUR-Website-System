<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ScdYear;
use App\Models\ContentNode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class FrontendController extends Controller
{
    public function about()
    {
        return view('frontend.pages.about');
    }

    public function contact()
    {
        return view('frontend.pages.contact');
    }

    public function announcements($year)
    {
        $year = ScdYear::where('year', $year)->firstOrFail();

        $announcements = ContentNode::where('scd_year_id', $year->id)
            ->where('category_group', 'announcement')
            ->whereNull('parent_id')
            ->orderBy('sequence')
            ->get();

        $orders = ContentNode::where('scd_year_id', $year->id)
            ->where('category_group', 'order')
            ->whereNull('parent_id')
            ->orderBy('sequence')
            ->get();

        return view('frontend.pages.announcements', compact(
            'year',
            'announcements',
            'orders'
        ));
    }

    public function contentSection($year, $sectionId)
    {
        // ปีการศึกษา
        $yearModel = ScdYear::where('year', $year)->firstOrFail();

        // หมวด (Section)
        $section = ContentNode::where('id', $sectionId)
            ->where('scd_year_id', $yearModel->id)
            ->firstOrFail();

        // รายการย่อยในหมวด
        $items = ContentNode::where('parent_id', $section->id)
            ->orderBy('sequence')
            ->get();

        $year = $yearModel;

        return view('frontend.pages.content-section', compact(
            'year',
            'section',
            'items'
        ));
    }

    public function viewFile($id, $filename = null)
    {
        $item = ContentNode::findOrFail($id);

        // ตรวจสอบว่าไฟล์มีอยู่จริงหรือไม่
        if (!Storage::disk('public')->exists($item->file_path)) {
            return back()->with('error', 'ไม่พบไฟล์ที่ต้องการดู');
        }

        // นับจำนวนการดู
        $item->increment('view_count');

        // ✅ เปิดดูไฟล์ในเบราว์เซอร์
        $filePath = storage_path('app/public/' . $item->file_path);

        return response()->make(file_get_contents($filePath), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Length' => filesize($filePath),
        ]);
    }

    public function downloadFile($id)
    {
        $item = ContentNode::findOrFail($id);

        // ตรวจสอบว่าไฟล์มีอยู่จริงหรือไม่
        if (!Storage::disk('public')->exists($item->file_path)) {
            return back()->with('error', 'ไม่พบไฟล์ที่ต้องการดาวน์โหลด');
        }

        // นับจำนวนดาวน์โหลด
        $item->increment('download_count');

        // ✅ บังคับดาวน์โหลด (ไม่เปิดดู)
        $filePath = storage_path('app/public/' . $item->file_path);
        $cleanName = preg_replace('/[^\p{L}\p{N}\s\-_.]/u', '', $item->name);
        $cleanName = trim($cleanName) ?: 'document';
        $encodedName = rawurlencode($cleanName . '.pdf');

        return response()->make(file_get_contents($filePath), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename*=UTF-8''{$encodedName}",
            'Content-Length' => filesize($filePath),
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function viewScdReport($year, $filename = null)
    {
        $year = ScdYear::with('report')->where('year', $year)->firstOrFail();

        if (!$year->report) {
            return back()->with('error', 'ไม่พบรายงาน SCD สำหรับปีนี้');
        }

        $report = $year->report;

        // ตรวจสอบว่าไฟล์มีอยู่จริงหรือไม่
        if (!Storage::disk('public')->exists($report->file_path)) {
            return back()->with('error', 'ไม่พบไฟล์รายงาน SCD');
        }

        // นับจำนวนดู (ถ้ามีฟิลด์ view_count)
        if (Schema::hasColumn('scd_reports', 'view_count')) {
            $report->increment('view_count');
        }

        // สร้างชื่อไฟล์ที่เหมาะสม
        $displayName = "รายงานผล SCD {$year->year}.pdf";
        $encodedName = rawurlencode($displayName);

        // ✅ เปิดดูไฟล์ในเบราว์เซอร์พร้อมชื่อไฟล์ที่เหมาะสม
        $filePath = storage_path('app/public/' . $report->file_path);

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"{$displayName}\"; filename*=UTF-8''{$encodedName}",
        ]);
    }

    public function downloadScdReport($year)
    {
        $year = ScdYear::with('report')->where('year', $year)->firstOrFail();

        if (!$year->report) {
            return back()->with('error', 'ไม่พบรายงาน SCD สำหรับปีนี้');
        }

        $report = $year->report;

        // ตรวจสอบว่าไฟล์มีอยู่จริงหรือไม่
        if (!Storage::disk('public')->exists($report->file_path)) {
            return back()->with('error', 'ไม่พบไฟล์รายงาน SCD');
        }

        // นับจำนวนดาวน์โหลด (ถ้ามีฟิลด์ download_count)
        if (Schema::hasColumn('scd_reports', 'download_count')) {
            $report->increment('download_count');
        }

        // สร้างชื่อไฟล์ที่เหมาะสม
        $filename = "รายงาน ARU-SCD{$year->year} - " . ($year->year + 543) . ".pdf";
        $encodedName = rawurlencode($filename);

        // ✅ บังคับดาวน์โหลด (ไม่เปิดดู) พร้อมชื่อไฟล์ที่เหมาะสม
        $filePath = storage_path('app/public/' . $report->file_path);

        return response()->make(file_get_contents($filePath), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename*=UTF-8''{$encodedName}",
            'Content-Length' => filesize($filePath),
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
