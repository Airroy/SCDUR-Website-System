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
        Schema::create('scd_years', function (Blueprint $table) {
            $table->id();
            $table->string('year', 4)->unique(); // ชื่อปี เช่น "2025"
            $table->date('created_date'); // วันที่สร้าง (ใช้เรียงลำดับ)
            $table->boolean('is_published')->default(false); // 0=Draft, 1=Public
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scd_years');
    }
};
