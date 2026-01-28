@props(['title', 'image' => null, 'year' => null, 'itemCount' => 0, 'report' => null, 'yearId' => null])

<section class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
    <div class="p-8">
        <div class="flex flex-col md:flex-row gap-6">
            {{-- รูปภาพหมวดหมู่ --}}
            @if ($image)
                <img src="{{ Storage::url($image) }}" alt="{{ $title }}"
                    class="w-full md:w-48 h-48 object-cover rounded-lg">
            @endif

            {{-- ข้อมูลหมวดหมู่ --}}
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $title }}</h1>

                <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                    {{-- ปี --}}
                    @if ($year)
                        <span class="flex items-center gap-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            ปี {{ $year }}
                        </span>
                    @endif

                    {{-- จำนวนรายการ --}}
                    <span class="flex items-center gap-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        {{ $itemCount }} รายการ
                    </span>
                </div>

                {{-- ปุ่มดาวน์โหลด SCD Report --}}
                @if ($report)
                    <a href="{{ Storage::url($report->file_path) }}" target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-[#af1a00] hover:bg-[#8b1500] text-white font-semibold rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>ดาวน์โหลด SCD Report</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>