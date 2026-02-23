<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * เปลี่ยนชื่อตารางให้มี prefix scd_
     * - announcements    → scd_announcements
     * - orders           → scd_directives
     * - content_sections → scd_contents
     * - banners          → scd_banners
     */
    public function up(): void
    {
        Schema::rename('announcements', 'scd_announcements');
        Schema::rename('orders', 'scd_directives');
        Schema::rename('content_sections', 'scd_contents');
        Schema::rename('banners', 'scd_banners');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('scd_announcements', 'announcements');
        Schema::rename('scd_directives', 'orders');
        Schema::rename('scd_contents', 'content_sections');
        Schema::rename('scd_banners', 'banners');
    }
};
