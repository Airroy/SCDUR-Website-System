<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * เปลี่ยนระบบจัดเรียง Banner จาก sequence (ลำดับ) เป็น category (หมวด 0/1)
     * - หมวด 0 = แสดงผล (เรียงตาม created_at DESC)
     * - หมวด 1 = ไม่แสดงผล (ซ่อน)
     */
    public function up(): void
    {
        Schema::table('scd_banners', function (Blueprint $table) {
            // ลบ foreign key ก่อน (เพราะมันอ้างอิง index เดียวกัน)
            $table->dropForeign('banners_scd_year_id_foreign');

            // ลบ unique constraint เดิม (ชื่อ index มาจากตอนตารางยังชื่อ banners)
            $table->dropIndex('banners_scd_year_id_sequence_unique');

            // ลบ column sequence
            $table->dropColumn('sequence');

            // เพิ่ม column category (0 = แสดง, 1 = ซ่อน)
            $table->tinyInteger('category')->default(0)->after('scd_year_id');

            // สร้าง foreign key ใหม่
            $table->foreign('scd_year_id')->references('id')->on('scd_years')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scd_banners', function (Blueprint $table) {
            // ลบ category
            $table->dropColumn('category');

            // เพิ่ม sequence กลับ
            $table->integer('sequence')->after('scd_year_id');
            $table->unique(['scd_year_id', 'sequence']);
        });
    }
};
