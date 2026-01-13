@props(['year'])

<div class="space-y-2 mb-4 text-sm">
    <div class="flex justify-between items-center py-2 border-b border-gray-100">
        <span class="text-gray-600">รายงานผล SCD</span>
        <span class="font-semibold text-gray-800">{{ $year->report ? '✓' : '-' }}</span>
    </div>
    <div class="flex justify-between items-center py-2 border-b border-gray-100">
        <span class="text-gray-600">Slider Banner</span>
        <span class="font-semibold text-gray-800">{{ $year->banners->count() }}</span>
    </div>
    <div class="flex justify-between items-center py-2 border-b border-gray-100">
        <span class="text-gray-600">ประกาศ/คำสั่ง</span>
        <span class="font-semibold text-gray-800">
            {{ $year->contentNodes->whereIn('category_group', ['announcement', 'order'])->count() }}
        </span>
    </div>
    <div class="flex justify-between items-center py-2">
        <span class="text-gray-600">Content Section</span>
        <span class="font-semibold text-gray-800">
            {{ $year->contentNodes->where('category_group', 'content_section')->count() }}
        </span>
    </div>
</div>
