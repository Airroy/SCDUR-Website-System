@props(['publishedYears' => []])

<!-- Top Bar (สีเหลือง) -->
<div class="bg-yellow-100 border-b border-yellow-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
        <p class="text-center text-sm text-gray-700">Phranakhon Si Ayutthaya Rajabhat University</p>
    </div>
</div>

<!-- Header Image Section -->
<div class="bg-white w-full">
    <img src="{{ asset('images/header-banner.jpg') }}" alt="Header Banner" class="w-full h-auto object-cover">
</div>

<!-- Navigation Bar - Fixed -->
<x-frontend.navigation :publishedYears="$publishedYears" />
