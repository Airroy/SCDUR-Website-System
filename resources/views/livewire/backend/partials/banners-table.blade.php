<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div
        class="px-4 sm:px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <h3 class="text-base sm:text-lg font-semibold text-gray-900">รูปภาพสไลด์ Banners</h3>
        <div class="flex items-center gap-2 w-full sm:w-auto">
            @if ($banners->where('category', 0)->count() > 1)
                <x-backend.action-button color="gray" action="openSortModal(0)" label="จัดลำดับ"
                    title="จัดการลำดับสไลด์" />
            @endif
            <x-backend.action-button color="red" action="openAddModal" label="เพิ่มสไลด์"
                title="เพิ่มรูปภาพสไลด์ใหม่" />
        </div>
    </div>

    <!-- Table or Empty State -->
    @if ($banners->isEmpty())
        <div class="px-4 sm:px-6 py-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                </path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">ยังไม่มีรูปภาพสไลด์</h3>
            <p class="mt-1 text-sm text-gray-500">เริ่มต้นโดยการเพิ่มรูปภาพสไลด์</p>
        </div>
    @else
        <!-- Mobile Card View (< md) -->
        <div class="md:hidden divide-y divide-gray-200">
            @foreach ($banners as $banner)
                <div class="p-4 {{ $banner->category == 0 ? 'hover:bg-gray-50' : 'bg-red-200 hover:bg-red-300' }}">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span
                                    class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-100 text-xs font-semibold text-gray-700">
                                    {{ $loop->iteration }}
                                </span>
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $banner->category == 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $banner->category == 0 ? 'แสดงผล' : 'ซ่อน' }}
                                </span>
                            </div>
                        </div>
                        @if ($banner->image_path && Storage::disk('public')->exists($banner->image_path))
                            <img src="{{ Storage::url($banner->image_path) }}" alt="Banner"
                                class="h-12 w-20 object-cover rounded shadow flex-shrink-0">
                        @endif
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @if ($banner->link_type === 'url' && $banner->link_url)
                            <x-backend.action-button color="url-badge" :href="$banner->link_url" label="URL"
                                target="_blank" />
                        @elseif ($banner->link_type === 'pdf' && $banner->pdf_path && Storage::disk('public')->exists($banner->pdf_path))
                            <x-backend.action-button color="pdf-badge" :href="Storage::url($banner->pdf_path)" label="PDF"
                                target="_blank" />
                        @endif
                        <x-backend.action-button color="yellow-outline" action="openEditModal({{ $banner->id }})"
                            label="แก้ไข" />
                        <x-backend.action-button color="red-outline" action="deleteBanner({{ $banner->id }})"
                            label="ลบ" confirm="คุณแน่ใจหรือไม่ว่าต้องการลบรายการนี้?" />
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Desktop Table View (>= md) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="w-20 px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ลำดับ</th>
                        <th
                            class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            รูปภาพ</th>
                        <th
                            class="w-28 px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            สถานะ</th>
                        <th
                            class="w-28 px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ไฟล์/ลิงค์</th>
                        <th
                            class="hidden lg:table-cell w-36 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            วันที่อัปเดตล่าสุด</th>
                        <th
                            class="w-56 px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            จัดการ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($banners as $banner)
                        <tr class="{{ $banner->category == 0 ? 'hover:bg-gray-50' : 'bg-red-200 hover:bg-red-300' }}">
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                @if ($banner->image_path && Storage::disk('public')->exists($banner->image_path))
                                    <img src="{{ Storage::url($banner->image_path) }}" alt="Banner"
                                        class="h-16 w-28 object-cover rounded shadow">
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 lg:px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $banner->category == 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $banner->category == 0 ? 'แสดงผล' : 'ซ่อน' }}
                                </span>
                            </td>
                            <td class="px-4 lg:px-6 py-4 text-sm">
                                @if ($banner->link_type === 'url' && $banner->link_url)
                                    <x-backend.action-button color="url-badge" :href="$banner->link_url" label="URL"
                                        target="_blank" />
                                @elseif ($banner->link_type === 'pdf' && $banner->pdf_path && Storage::disk('public')->exists($banner->pdf_path))
                                    <x-backend.action-button color="pdf-badge" :href="Storage::url($banner->pdf_path)" label="PDF"
                                        target="_blank" />
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="hidden lg:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $banner->updated_at->toThaiDateFull() }}
                            </td>
                            <td class="px-4 lg:px-6 py-4 text-sm font-medium">
                                <div class="flex flex-wrap items-center gap-2">
                                    <x-backend.action-button color="yellow-outline"
                                        action="openEditModal({{ $banner->id }})" label="แก้ไข" />
                                    <x-backend.action-button color="red-outline"
                                        action="deleteBanner({{ $banner->id }})" label="ลบ"
                                        confirm="คุณแน่ใจหรือไม่ว่าต้องการลบรายการนี้?" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
