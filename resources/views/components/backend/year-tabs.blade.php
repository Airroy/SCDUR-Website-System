@props(['selectedYear', 'currentPage'])

<div class="border-b border-gray-200 bg-white">
    <nav class="-mb-px flex justify-between sm:justify-start sm:space-x-8 px-2 sm:px-6" aria-label="Tabs">
        <a href="{{ route('admin.contents.index', ['year' => $selectedYear->year]) }}"
            class="border-b-2 {{ $currentPage === 'contents' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 sm:py-4 px-1 font-medium text-xs sm:text-sm flex items-center gap-1 sm:gap-2 flex-1 sm:flex-initial justify-center sm:justify-start">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                </path>
            </svg>
            <span class="text-[10px] sm:text-sm">ตัวชี้วัด Indicators</span>
        </a>
        <a href="{{ route('admin.reports.index', ['year' => $selectedYear->year]) }}"
            class="border-b-2 {{ $currentPage === 'reports' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 sm:py-4 px-1 font-medium text-xs sm:text-sm flex items-center gap-1 sm:gap-2 flex-1 sm:flex-initial justify-center sm:justify-start">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            <span class="text-[10px] sm:text-sm">รายงานผล SCD</span>
        </a>
        <a href="{{ route('admin.banners.index', ['year' => $selectedYear->year]) }}"
            class="border-b-2 {{ $currentPage === 'banners' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 sm:py-4 px-1 font-medium text-xs sm:text-sm flex items-center gap-1 sm:gap-2 flex-1 sm:flex-initial justify-center sm:justify-start">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                </path>
            </svg>
            <span class="text-[10px] sm:text-sm">รูปสไลด์ Banner</span>
        </a>
        <a href="{{ route('admin.announcements.index', ['year' => $selectedYear->year]) }}"
            class="border-b-2 {{ $currentPage === 'announcements' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 sm:py-4 px-1 font-medium text-xs sm:text-sm flex items-center gap-1 sm:gap-2 flex-1 sm:flex-initial justify-center sm:justify-start">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                </path>
            </svg>
            <span class="text-[10px] sm:text-sm">ประกาศ</span>
        </a>
        <a href="{{ route('admin.directives.index', ['year' => $selectedYear->year]) }}"
            class="border-b-2 {{ $currentPage === 'directives' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 sm:py-4 px-1 font-medium text-xs sm:text-sm flex items-center gap-1 sm:gap-2 flex-1 sm:flex-initial justify-center sm:justify-start">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                </path>
            </svg>
            <span class="text-[10px] sm:text-sm">คำสั่ง</span>
        </a>
    </nav>
</div>
