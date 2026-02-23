<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon; // เพิ่มบรรทัดนี้

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Components อยู่ใน resources/views/components/ 
        // ใช้ชื่อ file ตรงกับ component name แล้ว ไม่ต้อง register aliases

        // เพิ่ม Carbon Macro สำหรับแปลงเป็น พ.ศ.
        Carbon::macro('toThaiDateTimeFull', function () {
            $months = [
                1 => 'มกราคม',
                'กุมภาพันธ์',
                'มีนาคม',
                'เมษายน',
                'พฤษภาคม',
                'มิถุนายน',
                'กรกฎาคม',
                'สิงหาคม',
                'กันยายน',
                'ตุลาคม',
                'พฤศจิกายน',
                'ธันวาคม'
            ];

            $day = $this->format('j');
            $month = $months[(int)$this->format('n')];
            $year = (int)$this->format('Y') + 543;
            $time = $this->format('H:i');

            return "{$day} {$month} {$year} {$time}";
        });

        Carbon::macro('toThaiDateFull', function () {
            $months = [
                1 => 'มกราคม',
                'กุมภาพันธ์',
                'มีนาคม',
                'เมษายน',
                'พฤษภาคม',
                'มิถุนายน',
                'กรกฎาคม',
                'สิงหาคม',
                'กันยายน',
                'ตุลาคม',
                'พฤศจิกายน',
                'ธันวาคม'
            ];

            $day = $this->format('j');
            $month = $months[(int)$this->format('n')];
            $year = (int)$this->format('Y') + 543;

            return "{$day} {$month} {$year}";
        });
    }
}
