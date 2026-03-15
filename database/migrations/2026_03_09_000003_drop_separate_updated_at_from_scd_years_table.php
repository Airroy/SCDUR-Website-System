<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scd_years', function (Blueprint $table) {
            $columns = [
                'last_content_updated_at',
                'last_banner_updated_at',
                'last_report_updated_at',
                'last_announcement_updated_at',
                'last_directive_updated_at',
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('scd_years', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
    public function down(): void
    {
        Schema::table('scd_years', function (Blueprint $table) {
            $table->timestamp('last_content_updated_at')->nullable();
            $table->timestamp('last_banner_updated_at')->nullable();
            $table->timestamp('last_report_updated_at')->nullable();
            $table->timestamp('last_announcement_updated_at')->nullable();
            $table->timestamp('last_directive_updated_at')->nullable();
        });
    }
};
