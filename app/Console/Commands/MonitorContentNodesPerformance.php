<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Announcement;
use App\Models\Order;
use App\Models\ContentSection;
use Illuminate\Support\Facades\DB;

class MonitorContentNodesPerformance extends Command
{
    protected $signature = 'content:monitor';
    protected $description = 'Monitor content tables performance and size';

    public function handle()
    {
        $this->info('Content Tables Performance Monitoring');
        $this->info('=====================================');

        // Table size info
        $announcementCount = Announcement::count();
        $orderCount = Order::count();
        $contentSectionCount = ContentSection::count();
        $totalRecords = $announcementCount + $orderCount + $contentSectionCount;

        $this->table(['Metric', 'Value'], [
            ['Total Records', number_format($totalRecords)],
            ['Announcements', number_format($announcementCount)],
            ['Orders', number_format($orderCount)],
            ['Content Sections', number_format($contentSectionCount)],
        ]);

        // Query performance test
        $this->info("\nQuery Performance Test:");

        $start = microtime(true);
        Announcement::whereNull('parent_id')
            ->orderBy('sequence')
            ->limit(10)
            ->get();
        $announcementTime = (microtime(true) - $start) * 1000;

        $start = microtime(true);
        Order::whereNull('parent_id')
            ->orderBy('sequence')
            ->limit(10)
            ->get();
        $orderTime = (microtime(true) - $start) * 1000;

        $start = microtime(true);
        ContentSection::whereNull('parent_id')
            ->orderBy('sequence')
            ->limit(10)
            ->get();
        $contentTime = (microtime(true) - $start) * 1000;

        $this->table(['Query Type', 'Time (ms)', 'Status'], [
            ['Announcements', round($announcementTime, 2), $announcementTime < 100 ? '✅ Good' : '⚠️ Slow'],
            ['Orders', round($orderTime, 2), $orderTime < 100 ? '✅ Good' : '⚠️ Slow'],
            ['Content Sections', round($contentTime, 2), $contentTime < 100 ? '✅ Good' : '⚠️ Slow'],
        ]);

        // Recommendations
        $this->info("\nRecommendations:");
        if ($totalRecords > 50000) {
            $this->warn("⚠️ Consider table partitioning or optimization");
        }
        if ($announcementTime > 100 || $orderTime > 100 || $contentTime > 100) {
            $this->warn("⚠️ Consider adding more specific indexes");
        }
        if ($totalRecords < 10000) {
            $this->info("✅ Current structure is optimal");
        }
    }
}
