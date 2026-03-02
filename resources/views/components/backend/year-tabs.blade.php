@props(['selectedYear', 'currentPage'])

@php
$tabs = [
    ['page' => 'contents',      'route' => 'admin.contents.index',      'label' => 'ตัวชี้วัด Indicators', 'short' => 'ตัวชี้วัด',
     'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
    ['page' => 'reports',       'route' => 'admin.reports.index',       'label' => 'รายงานผล SCD',         'short' => 'รายงาน',
     'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
    ['page' => 'banners',       'route' => 'admin.banners.index',       'label' => 'รูปสไลด์ Banner',      'short' => 'Banner',
     'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
    ['page' => 'announcements', 'route' => 'admin.announcements.index', 'label' => 'ประกาศ',               'short' => 'ประกาศ',
     'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z'],
    ['page' => 'directives',    'route' => 'admin.directives.index',    'label' => 'คำสั่ง',               'short' => 'คำสั่ง',
     'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
];
@endphp

<div class="border-b border-gray-200 bg-white sticky top-0 z-40 lg:z-50"
     :class="sidebarOpen && window.innerWidth < 1024 ? 'opacity-0 pointer-events-none' : 'opacity-100'" class="transition-all duration-500 ease-in-out">

    {{-- จอใหญ่ (sm ขึ้นไป) --}}
    <nav class="-mb-px hidden sm:flex px-2 sm:px-6" aria-label="Tabs">
        @foreach ($tabs as $tab)
            @php $active = $currentPage === $tab['page']; @endphp
            <a href="{{ route($tab['route'], ['year' => $selectedYear->year]) }}"
                class="border-b-2 {{ $active ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 sm:py-5 px-1 font-medium flex flex-col sm:flex-row items-center gap-1 sm:gap-2 flex-1 justify-center transition-colors">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}" />
                </svg>
                <span class="font-semibold text-xl">{{ $tab['label'] }}</span>
            </a>
        @endforeach
    </nav>

    {{-- มือถือ --}}
    <nav class="-mb-px flex sm:hidden" aria-label="Tabs">
        @foreach ($tabs as $tab)
            @php $active = $currentPage === $tab['page']; @endphp
            <a href="{{ route($tab['route'], ['year' => $selectedYear->year]) }}"
                class="border-b-2 {{ $active ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} flex-1 flex flex-col items-center justify-center py-2 px-1 gap-1 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}" />
                </svg>
                <span class="font-semibold text-xs text-center leading-tight">{{ $tab['short'] }}</span>
            </a>
        @endforeach
    </nav>

</div>