<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Components อยู่ใน resources/views/components/ 
        // ใช้ชื่อ file ตรงกับ component name แล้ว ไม่ต้อง register aliases
    }
}
