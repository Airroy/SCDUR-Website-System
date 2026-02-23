<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next 
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ตรวจสอบว่า user ได้ login แล้วหรือไม่
        if (!auth()->check()) {
            // ถ้ายังไม่ได้ login ให้แสดง 404 แทนการ redirect
            abort(404);
        }

        return $next($request);
    }
}
