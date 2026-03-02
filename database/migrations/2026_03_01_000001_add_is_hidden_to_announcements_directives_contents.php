<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * เพิ่มคอลัมน์ is_hidden (ซ่อน/แสดง) ให้กับ 3 ตาราง:
     * - scd_announcements (ประกาศ)
     * - scd_directives (คำสั่ง)
     * - scd_contents (ตัวชี้วัด)
     */
    public function up(): void
    {
        Schema::table('scd_announcements', function (Blueprint $table) {
            $table->boolean('is_hidden')->default(false)->after('download_count');
        });

        Schema::table('scd_directives', function (Blueprint $table) {
            $table->boolean('is_hidden')->default(false)->after('download_count');
        });

        Schema::table('scd_contents', function (Blueprint $table) {
            $table->boolean('is_hidden')->default(false)->after('download_count');
        });
    }

    public function down(): void
    {
        Schema::table('scd_announcements', function (Blueprint $table) {
            $table->dropColumn('is_hidden');
        });

        Schema::table('scd_directives', function (Blueprint $table) {
            $table->dropColumn('is_hidden');
        });

        Schema::table('scd_contents', function (Blueprint $table) {
            $table->dropColumn('is_hidden');
        });
    }
};
