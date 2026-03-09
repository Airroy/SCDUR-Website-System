<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\AnnouncementOrderController;
use Illuminate\Support\Facades\Route;

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/scd/{year}', [HomeController::class, 'index'])->where('year', '[0-9]{4}')->name('scd.year');
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');

// ประกาศ/คำสั่ง
Route::get('/announcements-directives/{year}', [FrontendController::class, 'announcements'])->where('year', '[0-9]{4}')->name('announcements');
Route::get('/announcements-directives/{year}/folder/{folder}', [FrontendController::class, 'announcementFolder'])->where('year', '[0-9]{4}')->name('announcements.folder');

// ประกาศและคำสั่ง (แบบใหม่ - รวมกัน)
Route::get('/year/{year}/announcements-orders', [AnnouncementOrderController::class, 'index'])
    ->where('year', '[0-9]{4}')
    ->name('announcements-orders');

// ดู/ดาวน์โหลดเอกสาร (นับจำนวน)
Route::get('/file/{source}/{id}/view/{filename}', [FrontendController::class, 'viewFile'])->where(['source' => 'announcement|directive|content', 'filename' => '.*'])->name('file.view');
Route::get('/file/{source}/{id}/download', [FrontendController::class, 'downloadFile'])->where('source', 'announcement|directive|content')->name('file.download');

// Banner PDF View
Route::get('/banner/{id}/view/{filename}', [FrontendController::class, 'viewBannerPdf'])->where(['id' => '[0-9]+', 'filename' => '.*'])->name('banner.pdf.view');

// SCD Box / Content Section
Route::get('/scd/{year}/{section}', [FrontendController::class, 'contentSection'])->where(['year' => '[0-9]{4}', 'section' => '.+'])->name('scd.section');
Route::get('/scd/{year}/{sectionId}/folder/{folder}', [FrontendController::class, 'contentSectionFolder'])->where('year', '[0-9]{4}')->name('scd.section.folder');

// SCD Report View & Download
Route::get('/scd-report/{year}/view/{filename}', [FrontendController::class, 'viewScdReport'])->where(['year' => '[0-9]{4}', 'filename' => '.*'])->name('scd-report.view');
Route::get('/scd-report/{year}/download', [FrontendController::class, 'downloadScdReport'])->where('year', '[0-9]{4}')->name('scd-report.download');

//ปิดหน้า login ให้ไปที่หน้าแรก
Route::redirect('/login', '/');

// ⚠️ Admin Routes - ป้องกันด้วย 404
Route::prefix('admin')->name('admin.')->middleware(['admin.auth'])->group(function () {

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
    Route::get('/announcements/{year}/folder/{folderId}', \App\Livewire\Backend\AnnouncementsIndex::class)->name('announcements.folder'); // ✅ เพิ่มใหม่

    // Directives Management
    Route::get('/directives/{year?}', \App\Livewire\Backend\AnnouncementsIndex::class)->name('directives.index');
    Route::get('/directives/{year}/folder/{folderId}', \App\Livewire\Backend\AnnouncementsIndex::class)->name('directives.folder'); // ✅ เพิ่มใหม่

    // Contents Management
    Route::get('/contents/{year?}', \App\Livewire\Backend\ContentsIndex::class)->name('contents.index');
    Route::get('/contents/{year}/folder/{folderId}', \App\Livewire\Backend\ContentsIndex::class)->name('contents.folder');

    // Profile
    Route::view('/profile', 'admin.pages.profile')->name('profile');
});

require __DIR__ . '/auth.php';
