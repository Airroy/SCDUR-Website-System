<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ScdYear;
use App\Models\Content;
use Illuminate\Http\Request;

class AnnouncementOrderController extends Controller
{
    /**
     * แสดงหน้าประกาศและคำสั่ง
     */
    public function index($yearId)
    {
        // ดึงข้อมูลปี
        $year = ScdYear::findOrFail($yearId);
        
        // ดึงข้อมูลประกาศแบบ tree structure
        $announcements = Content::where('scd_year_id', $yearId)
            ->where('category', 'announcement')
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('sequence')
            ->get();
        
        // ดึงข้อมูลคำสั่งแบบ tree structure
        $orders = Content::where('scd_year_id', $yearId)
            ->where('category', 'order')
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('sequence')
            ->get();
        
        return view('frontend.announcements-orders', compact('year', 'announcements', 'orders'));
    }
}