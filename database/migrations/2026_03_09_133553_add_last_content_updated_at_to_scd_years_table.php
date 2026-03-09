<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scd_years', function (Blueprint $table) {
            $table->timestamp('last_content_updated_at')->nullable()->after('is_published');
        });
    }
    public function down(): void
    {
        Schema::table('scd_years', function (Blueprint $table) {
            $table->dropColumn('last_content_updated_at');
        });
    }
};
