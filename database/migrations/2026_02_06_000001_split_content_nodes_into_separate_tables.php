<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * แยกตาราง content_nodes ออกเป็น 3 ตาราง:
     * - announcements (ประกาศ)
     * - orders (คำสั่ง)
     * - content_sections (ข้อมูล SCD ย่อย)
     */
    public function up(): void
    {
        // 1. สร้างตาราง announcements (ประกาศ)
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scd_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('announcements')->cascadeOnDelete();

            $table->string('type'); // 'folder' (หมวดหมู่), 'file' (ไฟล์ปลายทาง)
            $table->string('name'); // ชื่อหมวดหมู่ หรือ ชื่อหัวข้อ
            $table->integer('sequence'); // ลำดับ

            $table->string('image_path')->nullable(); // รูปปก
            $table->string('file_path')->nullable(); // ไฟล์ PDF (เฉพาะ type=file)

            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('download_count')->default(0);

            $table->timestamps();

            // ในปีเดียวกัน + parent เดียวกัน sequence ห้ามซ้ำ
            $table->unique(['scd_year_id', 'parent_id', 'sequence'], 'announcements_unique');

            // Performance indexes
            $table->index(['scd_year_id', 'parent_id'], 'idx_announcements_year_parent');
            $table->index(['parent_id', 'sequence'], 'idx_announcements_parent_sequence');
            $table->index(['type', 'sequence'], 'idx_announcements_type_sequence');
        });

        // 2. สร้างตาราง orders (คำสั่ง)
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scd_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('orders')->cascadeOnDelete();

            $table->string('type'); // 'folder' (หมวดหมู่), 'file' (ไฟล์ปลายทาง)
            $table->string('name'); // ชื่อหมวดหมู่ หรือ ชื่อหัวข้อ
            $table->integer('sequence'); // ลำดับ

            $table->string('image_path')->nullable(); // รูปปก
            $table->string('file_path')->nullable(); // ไฟล์ PDF (เฉพาะ type=file)

            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('download_count')->default(0);

            $table->timestamps();

            // ในปีเดียวกัน + parent เดียวกัน sequence ห้ามซ้ำ
            $table->unique(['scd_year_id', 'parent_id', 'sequence'], 'orders_unique');

            // Performance indexes
            $table->index(['scd_year_id', 'parent_id'], 'idx_orders_year_parent');
            $table->index(['parent_id', 'sequence'], 'idx_orders_parent_sequence');
            $table->index(['type', 'sequence'], 'idx_orders_type_sequence');
        });

        // 3. สร้างตาราง content_sections (ข้อมูล SCD ย่อย)
        Schema::create('content_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scd_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('content_sections')->cascadeOnDelete();

            $table->string('type'); // 'folder' (หมวดหมู่), 'file' (ไฟล์ปลายทาง)
            $table->string('name'); // ชื่อหมวดหมู่ หรือ ชื่อหัวข้อ
            $table->integer('sequence'); // ลำดับ

            $table->string('image_path')->nullable(); // รูปปก
            $table->string('file_path')->nullable(); // ไฟล์ PDF (เฉพาะ type=file)

            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('download_count')->default(0);

            $table->timestamps();

            // ในปีเดียวกัน + parent เดียวกัน sequence ห้ามซ้ำ
            $table->unique(['scd_year_id', 'parent_id', 'sequence'], 'content_sections_unique');

            // Performance indexes
            $table->index(['scd_year_id', 'parent_id'], 'idx_content_sections_year_parent');
            $table->index(['parent_id', 'sequence'], 'idx_content_sections_parent_sequence');
            $table->index(['type', 'sequence'], 'idx_content_sections_type_sequence');
        });

        // 4. ลบตาราง content_nodes
        Schema::dropIfExists('content_nodes');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ลบ 3 ตารางใหม่
        Schema::dropIfExists('content_sections');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('announcements');

        // สร้างตาราง content_nodes กลับมา
        Schema::create('content_nodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scd_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('content_nodes')->cascadeOnDelete();

            $table->string('category_group');
            $table->string('type');
            $table->string('name');
            $table->integer('sequence');

            $table->string('image_path')->nullable();
            $table->string('file_path')->nullable();

            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('download_count')->default(0);

            $table->timestamps();

            $table->unique(['scd_year_id', 'parent_id', 'category_group', 'sequence'], 'content_nodes_unique');

            $table->index(['category_group', 'scd_year_id', 'parent_id'], 'idx_category_year_parent');
            $table->index(['category_group', 'type', 'sequence'], 'idx_category_type_sequence');
            $table->index(['scd_year_id', 'sequence'], 'idx_year_sequence');
            $table->index(['parent_id', 'sequence'], 'idx_parent_sequence');
        });
    }
};
