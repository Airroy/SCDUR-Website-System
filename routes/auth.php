<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    // Register route - ปิดไว้เพื่อไม่ให้สมัครสมาชิกใหม่ได้
    // Route::get('register', \App\Livewire\Auth\Register::class)
    //     ->name('register');

    Route::get('login', \App\Livewire\Auth\Login::class)
        ->name('login');

    // Forgot/Reset Password - ปิดไว้เพราะยังไม่ได้ตั้งค่า Mail
    // Volt::route('forgot-password', 'auth.forgot-password')
    //     ->name('password.request');

    // Volt::route('reset-password/{token}', 'auth.reset-password')
    //     ->name('password.reset');
});

Route::middleware('auth')->group(function () {
    // Verify Email - ปิดไว้เพราะยังไม่ได้ตั้งค่า Mail
    // Volt::route('verify-email', 'auth.verify-email')
    //     ->name('verification.notice');

    // Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
    //     ->middleware(['signed', 'throttle:6,1'])
    //     ->name('verification.verify');

    // Volt::route('confirm-password', 'auth.confirm-password')
    //     ->name('password.confirm');
    
    Route::post('logout', function () {
        Auth::guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});
