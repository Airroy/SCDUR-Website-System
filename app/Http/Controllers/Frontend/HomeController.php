<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ScdYear;
use App\Models\Announcement;
use App\Models\ContentSection;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request, $year = null)
    {
        // ===============================
        // ดึงปีที่ publish แล้ว
        // ===============================
        $publishedYears = ScdYear::where('is_published', true)
            ->orderBy('year', 'desc')
            ->get();


        // ===============================
        // เลือกปีที่ใช้งาน (รับจาก route param /scd/{year})
        // ===============================
        $activeYear = $year
            ? ScdYear::where('year', $year)->first()
            : $publishedYears->first();


        // ===============================
        // ดึงประกาศ
        // ===============================
        $announcements = $activeYear
            ? Announcement::where('scd_year_id', $activeYear->id)
            ->whereNull('parent_id')
            ->orderBy('sequence')
            ->limit(6)
            ->get()
            : collect([]);


        // ===============================
        // ดึงหมวดเนื้อหา (content)
        // ===============================
        $contentSections = $activeYear
            ? ContentSection::where('scd_year_id', $activeYear->id)
            ->whereNull('parent_id')
            ->orderBy('sequence')
            ->get()
            : collect([]);


        // ===============================
        // ส่งไปหน้า View
        // ===============================
        return view('frontend.pages.home', [
            'activeYear'       => $activeYear,
            'publishedYears'   => $publishedYears,
            'announcements'    => $announcements,
            'contentSections'  => $contentSections,
            'isYearPage'       => $year !== null,  // เพื่อแยกว่าเป็นหน้าปีหรือหน้าหลัก
        ]);
    }
}
