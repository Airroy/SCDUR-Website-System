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
        Schema::create('scd_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scd_year_id')->constrained()->cascadeOnDelete();
            $table->string('file_name'); // ชื่อไฟล์ที่ตั้งเอง
            $table->string('file_path'); // path ไฟล์ PDF
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scd_reports');
    }
};
