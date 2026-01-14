<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin System - SCD</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @livewireStyles
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50" x-data="{ sidebarOpen: false, userMenuOpen: false }">
    
    <!-- Top Navigation Bar -->
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <!-- Mobile Menu Button -->
                    <button @click="sidebarOpen = !sidebarOpen" 
                            type="button" 
                            class="sm:hidden inline-flex items-center p-2 text-red-600 rounded-lg hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h10"/>
                        </svg>
                    </button>
                    <!-- Logo -->
                    <a href="{{ route('admin.dashboard') }}" class="flex ms-2 md:me-24">
                        <span class="self-center text-xl font-bold text-red-600 whitespace-nowrap">ระบบจัดการ SCDUR</span>
                    </a>
                </div>
                
                <!-- User Menu -->
                <div class="flex items-center">
                    <div class="flex items-center ms-3 relative">
                        <button @click="userMenuOpen = !userMenuOpen" 
                                type="button" 
                                class="flex items-center text-sm bg-red-600 rounded-full focus:ring-4 focus:ring-red-300">
                            <span class="sr-only">Open user menu</span>
                            <div class="w-8 h-8 rounded-full bg-red-600 flex items-center justify-center text-white font-semibold">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="userMenuOpen" 
                             @click.outside="userMenuOpen = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 top-full mt-2 z-50 w-48 bg-white border border-gray-200 rounded-lg shadow-lg"
                             style="display: none;">
                            <div class="px-4 py-3 border-b border-gray-200">
                                <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                <p class="text-sm text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <ul class="py-2 text-sm text-gray-700">
                                <li>
                                    <a href="{{ route('admin.dashboard') }}" 
                                       class="block px-4 py-2 hover:bg-red-50 hover:text-red-600">
                                        Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.profile') }}" 
                                       class="block px-4 py-2 hover:bg-red-50 hover:text-red-600">
                                        โปรไฟล์
                                    </a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full text-left px-4 py-2 hover:bg-red-50 hover:text-red-600">
                                            ออกจากระบบ
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed top-0 left-0 z-40 w-64 h-screen pt-16 transition-transform sm:translate-x-0 bg-white border-r border-gray-200"
           aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
            <!-- Navigation Menu -->
            <ul class="space-y-2 font-medium mt-4">
                <li>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-2 py-3 rounded-lg transition-colors text-lg {{ request()->routeIs('admin.dashboard') ? 'bg-red-600 text-white' : 'text-gray-600 hover:bg-red-50 hover:text-red-600' }} group">
                        <svg class="w-6 h-6 transition duration-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.scd-years.index') }}" 
                       class="flex items-center px-2 py-3 rounded-lg transition-colors text-lg {{ request()->routeIs('admin.scd-years.*') || request()->routeIs('admin.years.*') ? 'bg-red-600 text-white' : 'text-gray-600 hover:bg-red-50 hover:text-red-600' }} group">
                        <svg class="w-6 h-6 transition duration-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">จัดการปี SCD</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="p-4 sm:ml-64 mt-14">
        <!-- Page Header -->
        @hasSection('header')
            <header class="mb-6 pt-6 px-4 sm:px-6 lg:px-8">
                <!-- Back Button & Title -->
                <div class="flex items-center gap-4">
                    @hasSection('back-url')
                        <a href="@yield('back-url')" class="text-gray-600 hover:text-gray-800 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                    @endif
                    <div class="flex-1">
                        @yield('header')
                        
                        <!-- Breadcrumbs inline under title -->
                        @hasSection('breadcrumbs')
                            <nav class="flex items-center gap-2 text-sm sm:text-base mt-1">
                                @yield('breadcrumbs')
                            </nav>
                        @endif
                    </div>
                </div>
            </header>
        @endif

        <!-- Main Content Area -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Notification Popup -->
    <x-admin::notification />
    
    <!-- Confirmation Modal -->
    <x-admin::confirm-modal />

    @livewireScripts
    @stack('scripts')
</body>
</html>