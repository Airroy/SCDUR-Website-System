@props(['publishedYears' => []])

<!-- Top Bar (สีเหลือง) -->
<div class="bg-brand-yellow border-b border-brand-yellow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2.5">
        <p class="text-center text-base font-normal text-brand-blue">
            Phranakhon Si Ayutthaya Rajabhat University
        </p>
    </div>
</div>

<!-- Header Image Section - เพิ่ม bg-white -->
<div class="py-5 bg-white">
    <img src="{{ asset('images/header-banner.jpg') }}" 
         alt="Header Banner" 
         class="w-[1200px] h-auto block mx-auto">
</div>

<!-- Navigation Bar - Sticky -->
<x-frontend.navigation :publishedYears="$publishedYears" />