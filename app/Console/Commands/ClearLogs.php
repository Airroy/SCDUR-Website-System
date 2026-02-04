<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearLogs extends Command
{
    protected $signature = 'logs:clear {--days=7 : Number of days to keep}';
    protected $description = 'Clear old log files';

    public function handle()
    {
        $days = $this->option('days');
        $logPath = storage_path('logs');
        $files = File::files($logPath);
        $deleted = 0;

        foreach ($files as $file) {
            $fileTime = File::lastModified($file);
            $daysOld = now()->diffInDays(date('Y-m-d', $fileTime));

            if ($daysOld > $days) {
                File::delete($file);
                $deleted++;
                $this->info("Deleted: {$file->getFilename()}");
            }
        }

        $this->info("Deleted {$deleted} log file(s) older than {$days} days.");
    }
}
