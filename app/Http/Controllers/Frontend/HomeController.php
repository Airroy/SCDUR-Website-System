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
        // ดึงปีที่ publish แล้ว
        $publishedYears = ScdYear::where('is_published', true)
            ->orderBy('year', 'desc')
            ->get();

        // ใช้ปีที่เลือกหรือปีล่าสุด
        $selectedYearId = $request->query('year');
        $activeYear = $selectedYearId 
            ? ScdYear::find($selectedYearId)
            : $publishedYears->first();

        // ดึงประกาศ (announcements)
        $announcements = $activeYear
            ? ContentNode::where('scd_year_id', $activeYear->id)
                ->where('category_group', 'announcement')
                ->whereNull('parent_id')
                ->orderBy('sequence')
                ->limit(6)
                ->get()
            : collect([]);

        // ดึงหมวดหมู่เนื้อหา (content sections) - เฉพาะ root level
        $contentSections = $activeYear
            ? ContentNode::where('scd_year_id', $activeYear->id)
                ->where('category_group', 'content_section')
                ->whereNull('parent_id')
                ->orderBy('sequence')
                ->get()
            : collect([]);

        return view('frontend.pages.home', [
            'activeYear' => $activeYear,
            'publishedYears' => $publishedYears,
            'announcements' => $announcements,
            'contentSections' => $contentSections,
        ]);
    }
}
