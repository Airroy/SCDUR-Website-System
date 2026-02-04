@props([
    'title' => 'Indicators',
    'items',
    'emptyTitle' => 'ยังไม่มีรายการในหมวดหมู่นี้',
    'emptyMessage' => 'กรุณาตรวจสอบใหม่ภายหลัง',
    'backUrl' => null,
])

<div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 mb-8">
    {{-- Header --}}
    <div class="bg-[#af1a00] px-6 py-4 flex justify-center items-center">
        <h2 class="text-xl font-semibold text-white">{{ $title }}</h2>
    </div>

    {{-- Content - ใช้ document-tree เหมือนหน้าประกาศ --}}
    @if ($items->count() > 0)
        <div class="divide-y divide-gray-200">
            <x-frontend.document-tree :items="$items" />
        </div>
    @else
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $emptyTitle }}</h3>
            <p class="text-gray-500 mb-4">{{ $emptyMessage }}</p>
            @if ($backUrl)
                <a href="{{ $backUrl }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    กลับหน้าหลัก
                </a>
            @endif
        </div>
    @endif
</div>