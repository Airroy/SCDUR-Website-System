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
Route::get('/announcements/{year}', [FrontendController::class, 'announcements'])->name('announcements');
Route::get('/announcements/{year}/folder/{folder}', [FrontendController::class, 'announcementFolder'])->name('announcements.folder');

// ประกาศและคำสั่ง (แบบใหม่ - รวมกัน) ← เพิ่มใหม่
Route::get('/year/{yearId}/announcements-orders', [AnnouncementOrderController::class, 'index'])
    ->name('announcements-orders');

// ดู/ดาวน์โหลดเอกสาร (นับจำนวน)
Route::get('/document/{id}/view', [FrontendController::class, 'viewDocument'])->name('document.view');
Route::get('/document/{id}/download', [FrontendController::class, 'downloadDocument'])->name('document.download');

// ดู/ดาวน์โหลดไฟล์ Content Section (นับจำนวน)
Route::get('/file/{id}/view/{filename}', [FrontendController::class, 'viewFile'])->where('filename', '.*')->name('file.view');
Route::get('/file/{id}/download', [FrontendController::class, 'downloadFile'])->name('file.download');

// SCD Box / Content Section
Route::get('/content-section/{year}/{section}', [FrontendController::class, 'contentSection'])->name('content-section');
Route::get('/content-section/{year}/{section}/folder/{folder}', [FrontendController::class, 'contentSectionFolder'])->name('content-section.folder');

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
