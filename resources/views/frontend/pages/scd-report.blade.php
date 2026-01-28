<x-layouts.frontend>
    <x-slot:title>ARU-SCD{{ $year->year }} - รายงาน SCD</x-slot:title>

    <!-- Header Section - Improved Mobile -->
    <section class="py-8 sm:py-12 bg-gradient-to-b from-[#af1a00] to-[#8b1500]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-white text-center break-words">
                ARU-SCD{{ $year->year }}
            </h1>
            <p class="text-sm sm:text-base lg:text-lg text-white/90 text-center mt-3 sm:mt-4 px-4">
                รายงานมหาวิทยาลัยกับการพัฒนาชุมชนอย่างยั่งยืน ปี {{ $year->year + 543 }}
            </p>
        </div>
    </section>

    <!-- SCD Report PDF Download Section - Mobile Optimized -->
    @if ($report)
        <section class="py-6 sm:py-10 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 lg:p-8">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-6">
                        <!-- Icon & Text -->
                        <div class="flex items-start sm:items-center gap-3 sm:gap-4 flex-1 min-w-0">
                            <div class="flex-shrink-0">
                                <svg class="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 text-[#af1a00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base sm:text-lg lg:text-2xl font-semibold text-gray-800 break-words">
                                    {{ $report->file_name }}
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-500 mt-1">
                                    ดาวน์โหลดรายงาน SCD ฉบับเต็ม (PDF)
                                </p>
                            </div>
                        </div>
                        
                        <!-- Download Button -->
                        <a href="{{ Storage::url($report->file_path) }}" target="_blank"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 sm:px-6 py-2.5 sm:py-3 bg-[#af1a00] hover:bg-[#8b1500] text-white text-sm sm:text-base font-semibold rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg whitespace-nowrap">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>ดาวน์โหลดเอกสาร</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Content Sections Boxes - Fully Responsive Grid with Better Spacing -->
    @if ($contentSections->isNotEmpty())
        <section class="py-8 sm:py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Title -->
                <div class="mb-6 sm:mb-10">
                    <h2 class="text-xl sm:text-2xl lg:text-3xl font-semibold text-gray-800 text-center mb-2">
                        เนื้อหาภายในรายงาน
                    </h2>
                    <div class="w-20 sm:w-24 h-1 bg-[#af1a00] mx-auto"></div>
                </div>

                <!-- Content Grid - Responsive -->
                @php
                    $chunks = $contentSections->chunk(4);
                    $totalChunks = $chunks->count();
                @endphp

                <div class="space-y-8 sm:space-y-10 pb-8">
                    @foreach ($chunks as $chunkIndex => $chunk)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 lg:gap-8 {{ $chunkIndex < $totalChunks - 1 ? 'pb-8 sm:pb-10 border-b-2 border-gray-300' : '' }}">
                            @foreach ($chunk as $section)
                                <a href="{{ route('content.section', $section->id) }}" class="group block">
                                    <div class="transition-transform duration-300 hover:-translate-y-2">
                                        <!-- Image Container -->
                                        @if ($section->image_path)
                                            <div class="relative w-full aspect-video overflow-hidden rounded-lg shadow-[0_4px_15px_rgba(0,0,0,0.15)] group-hover:shadow-[0_8px_25px_rgba(0,0,0,0.25)] transition-shadow duration-300">
                                                <img src="{{ Storage::url($section->image_path) }}" 
                                                     alt="{{ $section->name }}" 
                                                     class="w-full h-full object-cover">
                                            </div>
                                        @else
                                            <div class="relative w-full aspect-video overflow-hidden rounded-lg shadow-[0_4px_15px_rgba(0,0,0,0.15)] group-hover:shadow-[0_8px_25px_rgba(0,0,0,0.25)] transition-shadow duration-300 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                                <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                        
                                        <!-- Text -->
                                        <p class="mt-3 sm:mt-4 text-base sm:text-lg font-semibold text-gray-800 group-hover:text-[#af1a00] transition-colors duration-200 break-words px-1">
                                            {{ $section->name }}
                                        </p>
                                        @if (isset($section->files_count) && $section->files_count > 0)
                                            <p class="mt-1 text-xs sm:text-sm text-gray-500 px-1">
                                                {{ $section->files_count }} รายการ
                                            </p>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @else
        <!-- Empty State -->
        <section class="py-8 sm:py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center py-12">
                    <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-lg sm:text-xl text-gray-500">ไม่มีข้อมูลเนื้อหาในขณะนี้</p>
                </div>
            </div>
        </section>
    @endif

</x-layouts.frontend>