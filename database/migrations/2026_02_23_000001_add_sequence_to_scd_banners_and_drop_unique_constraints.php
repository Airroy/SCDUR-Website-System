<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * เพิ่ม sequence กลับเข้า scd_banners เพื่อรองรับการจัดลำดับ
     * และลบ unique constraint ของ sequence ในตาราง announcements, directives, contents
     * เพื่อให้สามารถสลับลำดับได้โดยไม่ติด constraint
     */
    public function up(): void
    {
        // 1. เพิ่ม sequence ใน scd_banners (ถ้ายังไม่มี)
        if (!Schema::hasColumn('scd_banners', 'sequence')) {
            Schema::table('scd_banners', function (Blueprint $table) {
                $table->integer('sequence')->default(0)->after('category');
            });
        }

        // ตั้งค่า sequence เริ่มต้นตาม id (เรียงจากเก่าไปใหม่)
        DB::statement('SET @row_number = 0');
        DB::statement('UPDATE scd_banners SET sequence = (@row_number := @row_number + 1) ORDER BY category, created_at DESC');

        // 2. ลบ unique constraint ของ sequence ในตารางต่างๆ
        // เพื่อให้สามารถสลับลำดับได้โดยไม่ติด constraint
        Schema::table('scd_announcements', function (Blueprint $table) {
            $table->dropUnique('announcements_unique');
        });

        Schema::table('scd_directives', function (Blueprint $table) {
            $table->dropUnique('orders_unique');
        });

        Schema::table('scd_contents', function (Blueprint $table) {
            $table->dropUnique('content_sections_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // คืน unique constraint
        Schema::table('scd_announcements', function (Blueprint $table) {
            $table->unique(['scd_year_id', 'parent_id', 'sequence'], 'announcements_unique');
        });

        Schema::table('scd_directives', function (Blueprint $table) {
            $table->unique(['scd_year_id', 'parent_id', 'sequence'], 'orders_unique');
        });

        Schema::table('scd_contents', function (Blueprint $table) {
            $table->unique(['scd_year_id', 'parent_id', 'sequence'], 'content_sections_unique');
        });

        // ลบ sequence จาก scd_banners
        Schema::table('scd_banners', function (Blueprint $table) {
            $table->dropColumn('sequence');
        });
    }
};
