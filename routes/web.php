<?php

use App\Http\Controllers\Backend\ScdYearController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Route;

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::get('/announcements/{year}', [FrontendController::class, 'announcements'])->name('announcements');

// กลุ่ม Route สำหรับ Admin เท่านั้น
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    
    // 2.2.1 หน้า Dashboard
    Route::view('/dashboard', 'admin.pages.dashboard')->name('dashboard');

    // 2.2.2 จัดการปี SCD
    Route::resource('scd-years', ScdYearController::class)->except(['show', 'create', 'edit']);
    Route::post('scd-years/{year}/toggle-publish', [ScdYearController::class, 'togglePublish'])->name('years.toggle');
    
    // Sub-routes สำหรับจัดการข้อมูลภายในปี
    Route::controller(ScdYearController::class)->prefix('scd-years/{year}')->name('years.')->group(function () {
        Route::get('/manage', 'manage')->name('manage');
        Route::get('/reports', 'reports')->name('reports');
        Route::get('/banners', 'banners')->name('banners');
        Route::get('/announcements', 'announcements')->name('announcements');
        Route::get('/announcements/{type}', 'announcementCategory')->name('announcements.category');
        Route::get('/contents', 'contents')->name('contents');
    });
});

require __DIR__.'/auth.php';
