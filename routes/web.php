<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\AnnouncementOrderController;
use Illuminate\Support\Facades\Route;

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');

// ประกาศ/คำสั่ง (แบบเดิม - ถ้ายังใช้)
Route::get('/announcements/{year}', [FrontendController::class, 'announcements'])->where('year', '[0-9]{4}')->name('announcements');
Route::get('/announcements/{year}/folder/{folder}', [FrontendController::class, 'announcementFolder'])->where('year', '[0-9]{4}')->name('announcements.folder');

// ประกาศและคำสั่ง (แบบใหม่ - รวมกัน) ← เพิ่มใหม่
Route::get('/year/{year}/announcements-orders', [AnnouncementOrderController::class, 'index'])
    ->where('year', '[0-9]{4}')
    ->name('announcements-orders');

// ดู/ดาวน์โหลดเอกสาร (นับจำนวน)
Route::get('/document/{id}/view', [FrontendController::class, 'viewDocument'])->name('document.view');
Route::get('/document/{id}/download', [FrontendController::class, 'downloadDocument'])->name('document.download');

// ดู/ดาวน์โหลดไฟล์ Content Section (นับจำนวน)
Route::get('/file/{id}/view/{filename}', [FrontendController::class, 'viewFile'])->where('filename', '.*')->name('file.view');
Route::get('/file/{id}/download', [FrontendController::class, 'downloadFile'])->name('file.download');

// SCD Box / Content Section
Route::get('/content-section/{year}/{section}', [FrontendController::class, 'contentSection'])->where('year', '[0-9]{4}')->name('content-section');
Route::get('/content-section/{year}/{section}/folder/{folder}', [FrontendController::class, 'contentSectionFolder'])->where('year', '[0-9]{4}')->name('content-section.folder');

// SCD Report View & Download
Route::get('/scd-report/{year}/view/{filename}', [FrontendController::class, 'viewScdReport'])->where(['year' => '[0-9]{4}', 'filename' => '.*'])->name('scd-report.view');
Route::get('/scd-report/{year}/download', [FrontendController::class, 'downloadScdReport'])->where('year', '[0-9]{4}')->name('scd-report.download');

//ปิดหน้า login ให้ไปที่หน้าแรก
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::redirect('/login', '/');

// กลุ่ม Route สำหรับ Admin เท่านั้น
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard (หน้าแรก)
    Route::get('/dashboard', \App\Livewire\Backend\AdminDashboard::class)->name('dashboard');

    // Years Management
    Route::get('/years', \App\Livewire\Backend\YearsIndex::class)->name('years.index');

    // Reports Management
    Route::get('/reports/{year?}', \App\Livewire\Backend\ReportsIndex::class)->name('reports.index');

    // Banners Management
    Route::get('/banners/{year?}', \App\Livewire\Backend\BannersIndex::class)->name('banners.index');

    // Announcements Management
    Route::get('/announcements/{year?}', \App\Livewire\Backend\AnnouncementsIndex::class)->name('announcements.index');

    // Contents Management
    Route::get('/contents/{year?}', \App\Livewire\Backend\ContentsIndex::class)->name('contents.index');

    // Profile
    Route::view('/profile', 'admin.pages.profile')->name('profile');
});

require __DIR__ . '/auth.php';

// Error Pages Preview Routes - สำหรับดูหน้า Error Pages
Route::prefix('error-preview')->group(function () {
    Route::get('/403', function () {
        return view('errors.403');
    })->name('preview.error.403');

    Route::get('/404', function () {
        return view('errors.404');
    })->name('preview.error.404');

    Route::get('/419', function () {
        return view('errors.419');
    })->name('preview.error.419');

    Route::get('/500', function () {
        return view('errors.500');
    })->name('preview.error.500');

    Route::get('/503', function () {
        return view('errors.503');
    })->name('preview.error.503');
});

// Test Real Error Routes - ทดสอบ Error จริงๆ
Route::prefix('test-error')->group(function () {
    Route::get('/403', function () {
        abort(403, 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
    })->name('test.error.403');

    Route::get('/404', function () {
        abort(404, 'ไม่พบหน้าที่ต้องการ');
    })->name('test.error.404');

    Route::get('/419', function () {
        abort(419, 'Session หมดอายุ');
    })->name('test.error.419');

    Route::get('/500', function () {
        abort(500, 'เกิดข้อผิดพลาดภายในเซิร์ฟเวอร์');
    })->name('test.error.500');

    Route::get('/503', function () {
        abort(503, 'เซิร์ฟเวอร์ไม่พร้อมใช้งาน');
    })->name('test.error.503');
});

// Test Various Error Scenarios
Route::prefix('test-scenarios')->group(function () {
    // Test 404 - ลิงก์ที่ไม่มีอยู่จริง
    Route::get('/nonexistent', function () {
        return redirect('/this-page-does-not-exist-anywhere');
    })->name('test.404.redirect');

    // Test 500 - PHP Error
    Route::get('/php-error', function () {
        $array = null;
        return $array->undefinedMethod(); // จะเกิด error
    })->name('test.500.php');

    // Test Session Timeout (419)
    Route::get('/session-timeout', function () {
        session()->flush(); // ล้าง session
        return redirect()->back(); // แล้วเรียก CSRF token
    })->name('test.419.session');
});
