<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Http\Request;
use App\Http\Middleware\AccessLog; // ✅ เพิ่มบรรทัดนี้
use App\Http\Middleware\AdminAuth; // ✅ เพิ่ม AdminAuth middleware

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            RedirectIfAuthenticated::redirectUsing(fn() => route('admin.dashboard'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // ✅ Trust Cloudflare Proxy
        $middleware->trustProxies(
            at: '*',
            headers: Request::HEADER_X_FORWARDED_FOR |
                Request::HEADER_X_FORWARDED_HOST |
                Request::HEADER_X_FORWARDED_PORT |
                Request::HEADER_X_FORWARDED_PROTO
        );

        // ✅ Register AdminAuth middleware alias
        $middleware->alias([
            'admin.auth' => AdminAuth::class,
        ]);

        // ✅ เปิดใช้ AccessLog หลังจาก session middleware ทำงานแล้ว
        $middleware->web(append: [
            AccessLog::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
