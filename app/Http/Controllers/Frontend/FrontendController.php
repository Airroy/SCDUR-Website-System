<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ScdYear;
use App\Models\Announcement;
use App\Models\Banner;
use App\Models\Order;
use App\Models\ContentSection;
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

        $announcements = Announcement::where('scd_year_id', $year->id)
            ->whereNull('parent_id')
            ->where('is_hidden', false)
            ->orderBy('sequence')
            ->get();

        $orders = Order::where('scd_year_id', $year->id)
            ->whereNull('parent_id')
            ->where('is_hidden', false)
            ->orderBy('sequence')
            ->get();

        return view('frontend.pages.announcements', compact(
            'year',
            'announcements',
            'orders'
        ));
    }

    public function contentSection($year, $slug)
    {
        // ปีการศึกษา
        $yearModel = ScdYear::where('year', $year)->firstOrFail();

        // หมวด (Section) - ค้นหาจาก slug หรือ fallback id
        $sections = ContentSection::where('scd_year_id', $yearModel->id)
            ->whereNull('parent_id')
            ->where('is_hidden', false)
            ->get();

        // ลองหาจาก slug ปกติก่อน ถ้าไม่เจอลองหาจาก fallback format "section-{id}"
        $section = $sections->first(fn($s) => \Illuminate\Support\Str::slug($s->name) === $slug);

        if (!$section && preg_match('/^section-(\d+)$/', $slug, $matches)) {
            $section = $sections->first(fn($s) => $s->id == $matches[1]);
        }

        if (!$section) {
            abort(404);
        }

        // รายการย่อยในหมวด
        $items = ContentSection::where('parent_id', $section->id)
            ->where('is_hidden', false)
            ->orderBy('sequence')
            ->get();

        $year = $yearModel;

        return view('frontend.pages.content-section', compact(
            'year',
            'section',
            'items'
        ));
    }

    public function viewFile($source, $id, $filename = null)
    {
        // ค้นหาจากตารางที่ระบุ
        $model = match ($source) {
            'announcement' => Announcement::class,
            'directive' => Order::class,
            'content' => ContentSection::class,
        };
        $item = $model::findOrFail($id);

        // ตรวจสอบว่าไฟล์มีอยู่จริงหรือไม่
        if (!Storage::disk('public')->exists($item->file_path)) {
            return back()->with('error', 'ไม่พบไฟล์ที่ต้องการดู');
        }

        // นับจำนวนการดู (ป้องกันนับซ้ำใน session เดียวกัน)
        $sessionKey = "viewed_{$source}_{$id}";
        if (!session()->has($sessionKey)) {
            $item->increment('view_count');
            session()->put($sessionKey, true);
        }

        // ✅ เปิดดูไฟล์ในเบราว์เซอร์ (ใช้ชื่อไฟล์ต้นฉบับ)
        $filePath = storage_path('app/public/' . $item->file_path);
        $lastModified = filemtime($filePath);
        $etag = md5($item->file_path . $lastModified);

        $originalFilename = basename($item->file_path);
        $encodedName = rawurlencode($originalFilename);

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename*=UTF-8''{$encodedName}",
            'Cache-Control' => 'private, max-age=300',
            'ETag' => $etag,
            'Last-Modified' => gmdate('D, d M Y H:i:s', $lastModified) . ' GMT',
        ]);
    }

    public function downloadFile($source, $id)
    {
        // ค้นหาจากตารางที่ระบุ
        $model = match ($source) {
            'announcement' => Announcement::class,
            'directive' => Order::class,
            'content' => ContentSection::class,
        };
        $item = $model::findOrFail($id);

        // ตรวจสอบว่าไฟล์มีอยู่จริงหรือไม่
        if (!Storage::disk('public')->exists($item->file_path)) {
            return back()->with('error', 'ไม่พบไฟล์ที่ต้องการดาวน์โหลด');
        }

        // นับจำนวนดาวน์โหลด (ป้องกันนับซ้ำใน session เดียวกัน)
        $sessionKey = "downloaded_{$source}_{$id}";
        if (!session()->has($sessionKey)) {
            $item->increment('download_count');
            session()->put($sessionKey, true);
        }

        // ✅ บังคับดาวน์โหลด (ใช้ชื่อไฟล์ต้นฉบับ)
        $filePath = storage_path('app/public/' . $item->file_path);
        $originalFilename = basename($item->file_path);
        $encodedName = rawurlencode($originalFilename);

        return response()->download($filePath, $originalFilename, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename*=UTF-8''{$encodedName}",
        ]);
    }

    public function viewBannerPdf($id, $filename = null)
    {
        $banner = Banner::findOrFail($id);

        if (!$banner->pdf_path || !Storage::disk('public')->exists($banner->pdf_path)) {
            return back()->with('error', 'ไม่พบไฟล์ PDF');
        }

        $filePath = storage_path('app/public/' . $banner->pdf_path);
        $lastModified = filemtime($filePath);
        $etag = md5($banner->pdf_path . $lastModified);

        $originalFilename = basename($banner->pdf_path);
        $encodedName = rawurlencode($originalFilename);

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename*=UTF-8''{$encodedName}",
            'Cache-Control' => 'private, max-age=300',
            'ETag' => $etag,
            'Last-Modified' => gmdate('D, d M Y H:i:s', $lastModified) . ' GMT',
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

        // นับจำนวนดู (ป้องกันนับซ้ำใน session เดียวกัน)
        if (Schema::hasColumn('scd_reports', 'view_count')) {
            $sessionKey = "viewed_report_{$year->id}";
            if (!session()->has($sessionKey)) {
                $report->increment('view_count');
                session()->put($sessionKey, true);
            }
        }

        // ✅ เปิดดูไฟล์ในเบราว์เซอร์ (ใช้ชื่อไฟล์ต้นฉบับ)
        $filePath = storage_path('app/public/' . $report->file_path);
        $originalFilename = basename($report->file_path);
        $encodedName = rawurlencode($originalFilename);

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename*=UTF-8''{$encodedName}",
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

        // นับจำนวนดาวน์โหลด (ป้องกันนับซ้ำใน session เดียวกัน)
        if (Schema::hasColumn('scd_reports', 'download_count')) {
            $sessionKey = "downloaded_report_{$year->id}";
            if (!session()->has($sessionKey)) {
                $report->increment('download_count');
                session()->put($sessionKey, true);
            }
        }

        // ✅ บังคับดาวน์โหลด (ใช้ชื่อไฟล์ต้นฉบับ)
        $filePath = storage_path('app/public/' . $report->file_path);
        $originalFilename = basename($report->file_path);
        $encodedName = rawurlencode($originalFilename);

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
