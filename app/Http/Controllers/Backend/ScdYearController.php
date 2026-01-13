<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\StoreScdYearRequest;
use App\Http\Requests\Backend\UpdateScdYearRequest;
use App\Models\ScdYear;

class ScdYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.pages.scd-years.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreScdYearRequest $request)
    {
        $year = ScdYear::create([
            'year' => $request->year,
            'created_date' => $request->created_date,
            'is_published' => $request->is_published ?? false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'สร้างปี SCD สำเร็จ',
            'year' => $year
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScdYearRequest $request, ScdYear $year)
    {
        $year->update([
            'year' => $request->year_value,
            'created_date' => $request->created_date,
            'is_published' => $request->is_published ?? false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'อัปเดตปี SCD สำเร็จ',
            'year' => $year->fresh()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ScdYear $year)
    {
        $year->delete();

        return response()->json([
            'success' => true,
            'message' => 'ลบปี SCD สำเร็จ'
        ]);
    }

    /**
     * Toggle publish status.
     */
    public function togglePublish(ScdYear $year)
    {
        $year->update([
            'is_published' => !$year->is_published
        ]);

        return response()->json([
            'success' => true,
            'message' => 'เปลี่ยนสถานะการแสดงผลสำเร็จ',
            'is_published' => $year->is_published
        ]);
    }

    /**
     * Show manage page for a specific year.
     */
    public function manage(ScdYear $year)
    {
        return view('admin.pages.scd-years.manage', compact('year'));
    }

    /**
     * Show reports page for a specific year.
     */
    public function reports(ScdYear $year)
    {
        return view('admin.pages.scd-years.reports', compact('year'));
    }

    /**
     * Show banners page for a specific year.
     */
    public function banners(ScdYear $year)
    {
        return view('admin.pages.scd-years.banners', compact('year'));
    }

    /**
     * Show announcements page for a specific year.
     */
    public function announcements(ScdYear $year)
    {
        return view('admin.pages.scd-years.announcements', compact('year'));
    }

    /**
     * Show announcement category page for a specific year and type.
     */
    public function announcementCategory(ScdYear $year, string $type)
    {
        return view('admin.pages.scd-years.announcement-category', compact('year', 'type'));
    }

    /**
     * Show contents page for a specific year.
     */
    public function contents(ScdYear $year)
    {
        return view('admin.pages.scd-years.contents', compact('year'));
    }
}
