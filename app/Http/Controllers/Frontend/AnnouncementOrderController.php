<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ScdYear;
use App\Models\Announcement;
use App\Models\Order;
use Illuminate\Http\Request;

class AnnouncementOrderController extends Controller
{
    /**
     * แสดงหน้าประกาศและคำสั่ง
     */
    public function index($year)
    {
        // ดึงข้อมูลปี
        $yearModel = ScdYear::where('year', $year)->firstOrFail();

        // ดึงข้อมูลประกาศแบบ tree structure
        $announcements = Announcement::where('scd_year_id', $yearModel->id)
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('sequence')
            ->get();

        // ดึงข้อมูลคำสั่งแบบ tree structure
        $orders = Order::where('scd_year_id', $yearModel->id)
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('sequence')
            ->get();

        $year = $yearModel;

        return view('frontend.announcements-orders', compact('year', 'announcements', 'orders'));
    }
}
