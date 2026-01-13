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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scd_year_id')->constrained()->cascadeOnDelete();
            $table->integer('sequence')->unique(); // ลำดับ (ห้ามซ้ำ)
            $table->string('image_path'); // รูปภาพ
            $table->string('link_type')->default('url'); // 'url' หรือ 'pdf'
            $table->string('link_url')->nullable(); // กรณีเป็นลิงค์
            $table->string('pdf_name')->nullable(); // ชื่อไฟล์ PDF
            $table->string('pdf_path')->nullable(); // path ไฟล์ PDF
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
