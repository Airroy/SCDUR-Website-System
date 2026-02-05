<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ScdYear;
use App\Models\ContentNode;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // ===============================
        // ดึงปีที่ publish แล้ว
        // ===============================
        $publishedYears = ScdYear::where('is_published', true)
            ->orderBy('year', 'desc')
            ->get();


        // ===============================
        // เลือกปีที่ใช้งาน (รับเป็นปี ค.ศ. 4 หลัก)
        // ===============================
        $selectedYear = $request->query('year');

        $activeYear = $selectedYear
            ? ScdYear::where('year', $selectedYear)->first()
            : $publishedYears->first();


        // ===============================
        // ดึงประกาศ
        // ===============================
        $announcements = $activeYear
            ? ContentNode::where('scd_year_id', $activeYear->id)
            ->where('category_group', 'announcement')
            ->whereNull('parent_id')
            ->orderBy('sequence')
            ->limit(6)
            ->get()
            : collect([]);


        // ===============================
        // ดึงหมวดเนื้อหา (content)
        // *** แก้ตรงนี้ ***
        // ===============================
        $contentSections = $activeYear
            ? ContentNode::where('scd_year_id', $activeYear->id)
            ->where('category_group', 'content') // แก้จาก content_section เป็น content
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
        ]);
    }
}
