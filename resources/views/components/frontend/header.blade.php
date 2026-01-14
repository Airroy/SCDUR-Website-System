@props(['publishedYears' => []])

<!-- Top Bar (สีเหลือง #FFD87F) -->
<div class="bg-[#FFD87F] border-b border-[#FFD87F]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2.5">
        <p class="text-center text-base font-light text-[#1e3c72]">
            Phranakhon Si Ayutthaya Rajabhat University
        </p>
    </div>
</div>

<!-- Header Image Section -->
<div class="py-5">
    <img src="{{ asset('images/header-banner.jpg') }}" 
         alt="Header Banner" 
         class="w-[90%] max-w-[800px] h-auto block mx-auto rounded-[10px] shadow-[0_4px_15px_rgba(0,0,0,0.1)]">
</div>

<!-- Navigation Bar - Sticky -->
<x-frontend.navigation :publishedYears="$publishedYears" />