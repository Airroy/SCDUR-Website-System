<?php

namespace App\Http\Controllers;

use App\Models\ScdYear;
use App\Models\ContentNode;
use Illuminate\Http\Request;

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

        return view('frontend.pages.announcements', compact('year', 'announcements', 'orders'));
    }
}
