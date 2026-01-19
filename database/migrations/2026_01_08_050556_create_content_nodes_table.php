<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('content_nodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scd_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('content_nodes')->cascadeOnDelete(); // ซ้อนหมวดหมู่ได้ไม่จำกัด
            
            $table->string('category_group'); // ระบุกลุ่ม: 'announcement', 'order', 'content_section'
            $table->string('type'); // ระบุชนิด: 'folder' (หมวดหมู่), 'file' (ไฟล์ปลายทาง)
            $table->string('name'); // ชื่อหมวดหมู่ หรือ ชื่อหัวข้อ
            $table->integer('sequence'); // ลำดับ
            
            $table->string('image_path')->nullable(); // รูปปก (เฉพาะ Content Section)
            $table->string('file_path')->nullable(); // ไฟล์ PDF (เฉพาะ type=file)
            
            $table->timestamps();
            
            // แต่ละปีสามารถมี sequence เดียวกันได้ แต่ต้องไม่ซ้ำในปีเดียวกัน + parent เดียวกัน + category_group เดียวกัน
            $table->unique(['scd_year_id', 'parent_id', 'category_group', 'sequence'], 'content_nodes_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_nodes');
    }
};
