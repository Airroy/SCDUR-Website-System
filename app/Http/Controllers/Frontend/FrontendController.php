<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ScdYear;
use App\Models\ContentNode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function announcements($yearId)
    {
        $year = ScdYear::findOrFail($yearId);

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

    public function contentSection($yearId, $sectionId)
    {
        // ปีการศึกษา
        $year = ScdYear::findOrFail($yearId);

        // หมวด (Section)
        $section = ContentNode::where('id', $sectionId)
            ->where('scd_year_id', $yearId)
            ->firstOrFail();

        // รายการย่อยในหมวด
        $items = ContentNode::where('parent_id', $section->id)
            ->orderBy('sequence')
            ->get();

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
}
