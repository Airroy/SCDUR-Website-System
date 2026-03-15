<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AccessLog
{
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);
        $response = $next($request);
        $duration = round((microtime(true) - $startTime) * 1000, 2);

        $statusCode = method_exists($response, 'status')
            ? $response->status()
            : $response->getStatusCode();

        // วิเคราะห์ความเร็ว
        if ($duration < 100) {
            $speedStatus = 'FAST';
        } elseif ($duration < 500) {
            $speedStatus = 'NORMAL';
        } elseif ($duration < 1000) {
            $speedStatus = 'SLOW';
        } else {
            $speedStatus = 'VERY SLOW';
        }

        // Device Type
        $userAgent = $request->userAgent() ?? '';
        if (str_contains($userAgent, 'Mobile')) {
            $device = 'Mobile';
        } elseif (str_contains($userAgent, 'Tablet')) {
            $device = 'Tablet';
        } else {
            $device = 'Desktop';
        }

        // Browser Detection
        $browser = 'Unknown';
        if (str_contains($userAgent, 'Chrome') && !str_contains($userAgent, 'Edg')) {
            $browser = 'Chrome';
        } elseif (str_contains($userAgent, 'Firefox')) {
            $browser = 'Firefox';
        } elseif (str_contains($userAgent, 'Edg')) {
            $browser = 'Edge';
        } elseif (str_contains($userAgent, 'Safari') && !str_contains($userAgent, 'Chrome')) {
            $browser = 'Safari';
        }

        // Query String
        $queryString = $request->getQueryString() ? '?' . $request->getQueryString() : '';

        // Build log message
        $lines = [];
        $lines[] = "--- REQUEST LOG ---";
        $lines[] = "Time: " . now()->format('Y-m-d H:i:s');
        $lines[] = "Method: " . $request->method();
        $lines[] = "Status: {$statusCode} ({$this->getStatusText($statusCode)})";
        $lines[] = "Duration: {$duration}ms ({$speedStatus})";
        $lines[] = "Path: " . $request->path() . $queryString;
        $lines[] = "IP: " . $request->ip();
        $lines[] = "User: " . (auth()->check() ? (auth()->user()->email ?? auth()->user()->name) : 'Guest');
        $lines[] = "Device: {$device}";
        $lines[] = "Browser: {$browser}";

        // ✅ แก้ไข: ตรวจสอบว่า session มีหรือไม่ก่อนใช้งาน
        try {
            $sessionId = $request->hasSession() ? substr($request->session()->getId(), 0, 8) : 'none';
        } catch (\Exception $e) {
            $sessionId = 'error';
        }
        $lines[] = "Session: " . $sessionId;

        $lines[] = "Memory: " . round(memory_get_usage(true) / 1024 / 1024, 2) . "MB";

        // Query Parameters
        if ($request->query()) {
            $params = [];
            foreach ($request->query() as $key => $value) {
                $params[] = "$key=$value";
            }
            $lines[] = "Query: " . implode(', ', $params);
        }

        // POST/PUT Data
        if ($request->isMethod('POST') || $request->isMethod('PUT') || $request->isMethod('PATCH')) {
            $allData = $request->except(['password', 'password_confirmation', '_token']);
            if (count($allData) > 0) {
                $fields = array_keys(array_slice($allData, 0, 5));
                $dataStr = implode(', ', $fields);
                if (count($allData) > 5) {
                    $dataStr .= " (+" . (count($allData) - 5) . " more)";
                }
                $lines[] = "Data: {$dataStr}";
            }
        }

        // Redirect
        if ($statusCode >= 300 && $statusCode < 400) {
            $location = $response->headers->get('Location') ?? 'Unknown';
            $lines[] = "Redirect: {$location}";
        }

        // Referer
        $lines[] = "Referer: " . ($request->header('referer') ?? 'Direct');
        $lines[] = "-------------------";

        $logMessage = implode("\n", $lines);

        // Log ตาม severity
        if ($statusCode >= 500) {
            Log::error($logMessage);
        } elseif ($statusCode >= 400) {
            Log::warning($logMessage);
        } else {
            Log::info($logMessage);
        }

        return $response;
    }

    private function getStatusText($status)
    {
        $statusTexts = [
            200 => 'OK',
            201 => 'Created',
            204 => 'No Content',
            301 => 'Moved Permanently',
            302 => 'Found',
            304 => 'Not Modified',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            422 => 'Unprocessable Entity',
            429 => 'Too Many Requests',
            500 => 'Internal Server Error',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
        ];

        return $statusTexts[$status] ?? 'Unknown';
    }
}
