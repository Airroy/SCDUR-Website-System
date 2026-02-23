<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SCD System') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Cropper.js CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">

    @livewireStyles
    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">
        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 lg:hidden">
        </div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed lg:static inset-y-0 left-0 z-50 w-64 flex-shrink-0 bg-gradient-to-b from-red-600 to-red-700 transform transition-transform duration-300 ease-in-out lg:translate-x-0">
            @php
                $allYears = \App\Models\ScdYear::orderBy('year', 'desc')->get();
                $currentYear = $allYears->where('is_published', true)->first() ?? $allYears->first();
                $selectedYearParam = request()->route('year');
                $selectedYear = $selectedYearParam
                    ? $allYears->where('year', $selectedYearParam)->first()
                    : $currentYear;

                $activeRouteYear = request()->route('year');
                $isYearSubRoute =
                    request()->routeIs('admin.reports.*') ||
                    request()->routeIs('admin.banners.*') ||
                    request()->routeIs('admin.announcements.*') ||
                    request()->routeIs('admin.directives.*') ||
                    request()->routeIs('admin.contents.*');
                $initialActiveYear =
                    $isYearSubRoute && $activeRouteYear ? (int) $activeRouteYear : $allYears->first()?->year ?? 0;

                $isYearSectionActive = $isYearSubRoute || request()->routeIs('admin.years.*');
            @endphp

            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="px-6 py-6">
                    <div class="text-center">
                        <div
                            class="inline-flex items-center justify-center w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl mb-3 shadow-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h1 class="text-xl font-bold text-white mb-1">SCD System</h1>
                        <p class="text-xs text-red-100">ระบบจัดการข้อมูล</p>
                    </div>
                </div>

                <!-- Menu -->
                <nav class="flex-1 px-4 space-y-1.5 overflow-y-auto" x-data="{
                    yearDropdown: localStorage.getItem('yearDropdown') !== 'false',
                    toggleYearDropdown() {
                        this.yearDropdown = !this.yearDropdown;
                        localStorage.setItem('yearDropdown', this.yearDropdown);
                    }
                }">

                    <!-- หน้าหลัก -->
                    <a href="{{ route('admin.dashboard') }}"
                        @click="yearDropdown = false; localStorage.setItem('yearDropdown', 'false')"
                        :class="yearDropdown ? 'text-white hover:bg-white/20' :
                            '{{ request()->routeIs('admin.dashboard') ? 'bg-white text-red-600 shadow-xl' : 'text-white hover:bg-white/20' }}'"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        <span>หน้าหลัก</span>
                    </a>

                    <!-- จัดการปี SCD -->
                    @php $isYearActive = $isYearSubRoute || request()->routeIs('admin.years.*'); @endphp
                    <div>
                        <!-- Header button -->
                        <button type="button" @click="toggleYearDropdown()"
                            :class="yearDropdown
                                ?
                                'bg-white text-red-600 shadow-xl rounded-t-xl rounded-b-none' :
                                '{{ $isYearActive ? 'bg-white text-red-600 shadow-xl rounded-xl' : 'text-white hover:bg-white/20 rounded-xl' }}'"
                            class="w-full flex items-center gap-3 px-4 py-3 font-medium transition-all">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span class="flex-1 text-left">จัดการปี SCD</span>
                            <svg class="w-4 h-4 flex-shrink-0 opacity-60 transition-transform duration-200"
                                :class="yearDropdown ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown -->
                        <div x-show="yearDropdown" x-collapse x-cloak
                            class="bg-white rounded-b-xl shadow-lg overflow-hidden">

                            <!-- ปุ่มเพิ่ม/จัดการปี -->
                            <div class="px-3 pt-2 pb-2 border-b border-gray-100">
                                <a href="{{ route('admin.years.index') }}"
                                    class="flex items-center justify-center gap-1.5 w-full px-2 py-2.5 rounded-md text-[11px] font-semibold transition-all
            {{ request()->routeIs('admin.years.*') ? 'bg-red-600 text-white' : 'bg-red-100 text-red-600 hover:bg-red-100' }}">
                                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span>เพิ่ม / จัดการปี</span>
                                </a>
                            </div>

                            <!-- รายการปี -->
                            <div class="px-2 py-2 space-y-0.5">
                                @foreach ($allYears as $year)
                                    @php $isThisYearActive = $isYearSubRoute && $activeRouteYear == $year->year; @endphp
                                    <a href="{{ route('admin.contents.index', $year->year) }}"
                                        class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-all
                                            {{ $isThisYearActive ? 'bg-red-600 text-white shadow-sm' : 'text-gray-700 hover:bg-red-50 hover:text-red-600' }}">
                                        <span
                                            class="w-1.5 h-1.5 rounded-full flex-shrink-0
                                            {{ $year->is_published ? ($isThisYearActive ? 'bg-white/80' : 'bg-green-500') : ($isThisYearActive ? 'bg-white/30' : 'bg-gray-300') }}">
                                        </span>
                                        <span class="flex-1">ปี {{ $year->year }}</span>
                                        @if ($year->is_published)
                                            <span
                                                class="text-[10px] px-1.5 py-0.5 rounded-full font-medium
                                                {{ $isThisYearActive ? 'bg-white/20 text-white' : 'bg-green-100 text-green-700' }}">
                                                เผยแพร่
                                            </span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>

                        </div>
                    </div>

                </nav>

                <!-- User -->
                <div class="px-4 py-4" x-data="{ open: false }">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 flex items-center gap-3">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg">
                            <span
                                class="text-red-600 font-bold text-lg">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-red-100 truncate">แอดมิน</p>
                        </div>
                        <div class="relative">
                            <button @click.stop="open = !open"
                                class="p-2 text-white/80 hover:text-white hover:bg-white/20 rounded-lg transition-all">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z">
                                    </path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" @click.stop
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 bottom-full mb-2 w-48 bg-white rounded-lg shadow-xl overflow-hidden z-50"
                                style="display: none;">
                                <a href="{{ route('admin.profile') }}"
                                    class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    <span>จัดการโปรไฟล์</span>
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                            </path>
                                        </svg>
                                        <span>ออกจากระบบ</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Mobile Header with Hamburger -->
            <div class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h1 class="text-lg font-bold text-gray-900">SCD System</h1>
            </div>

            @if (isset($header))
                <header class="hidden lg:block bg-white border-b border-gray-200 px-6 py-4">
                    {{ $header }}
                </header>
            @endif

            <main class="flex-1 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Notification Component -->
    <x-notification />

    @livewireScripts

    <!-- Cropper.js Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

    @stack('scripts')
</body>

</html>
