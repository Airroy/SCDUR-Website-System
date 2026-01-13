<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'มหาวิทยาลัยกับการพัฒนาชุมชนอย่างยั่งยืน' }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @livewireStyles
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    
    @php
        $publishedYears = \App\Models\ScdYear::where('is_published', true)->orderBy('year', 'desc')->get();
    @endphp

    <!-- Header Component -->
    <x-frontend.header :publishedYears="$publishedYears" />

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer Component -->
    <x-frontend.footer />

    @livewireScripts
    @stack('scripts')
</body>
</html>
