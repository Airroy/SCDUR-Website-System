<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scd_years', function (Blueprint $table) {
            $table->timestamp('last_banner_updated_at')->nullable()->after('last_content_updated_at');
            $table->timestamp('last_report_updated_at')->nullable()->after('last_banner_updated_at');
            $table->timestamp('last_announcement_updated_at')->nullable()->after('last_report_updated_at');
            $table->timestamp('last_directive_updated_at')->nullable()->after('last_announcement_updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('scd_years', function (Blueprint $table) {
            $table->dropColumn([
                'last_banner_updated_at',
                'last_report_updated_at',
                'last_announcement_updated_at',
                'last_directive_updated_at',
            ]);
        });
    }
};
