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

    public function viewFile($id)
    {
        $item = ContentNode::findOrFail($id);
        
        // ตรวจสอบว่าไฟล์มีอยู่จริงหรือไม่
        if (!Storage::disk('public')->exists($item->file_path)) {
            return back()->with('error', 'ไม่พบไฟล์ที่ต้องการดู');
        }
        
        // นับจำนวนการดู
        $item->increment('view_count');
        
        // Redirect ไปยังไฟล์
        return redirect(Storage::disk('public')->url($item->file_path));
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
        
        // ทำความสะอาดชื่อไฟล์ - ลบ / และ \ ออก
        $cleanName = str_replace(['/', '\\'], '-', $item->name);
        
        // ดาวน์โหลดไฟล์ (บังคับเป็น .pdf)
        return Storage::disk('public')->download($item->file_path, $cleanName . '.pdf');
    }
}