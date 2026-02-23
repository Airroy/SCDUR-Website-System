@php
    $visibleBanners = $banners->where('category', 0);
    $hiddenBanners = $banners->where('category', 1);
@endphp

<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow">
        <div
            class="px-4 sm:px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-900">รูปภาพสไลด์</h3>
                <p class="text-xs text-gray-500 mt-0.5">สไลด์ในหมวด "แสดงผล" จะเรียงตามลำดับที่กำหนด</p>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button wire:click="openAddModal"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    เพิ่มรูปภาพสไลด์
                </button>
            </div>
        </div>
    </div>

    @if ($banners->isEmpty())
        <div class="bg-white rounded-lg shadow px-4 sm:px-6 py-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                </path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">ยังไม่มีรูปภาพสไลด์</h3>
            <p class="mt-1 text-sm text-gray-500">เริ่มต้นโดยการเพิ่มรูปภาพสไลด์</p>
        </div>
    @else
        {{-- ===== แสดงผล ===== --}}
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            {{-- Section Header --}}
            <div class="bg-green-600 px-4 sm:px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-white flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>
                    </svg>
                    <div>
                        <h4 class="text-base font-bold text-white">แสดงผล</h4>
                        <p class="text-xs text-green-200 mt-0.5">สไลด์เหล่านี้จะแสดงบนหน้าเว็บ</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if ($visibleBanners->count() > 1)
                        <button wire:click="openSortModal(0)"
                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-white/20 hover:bg-white/30 rounded-lg transition-colors"
                            title="จัดการลำดับ">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                            </svg>
                            จัดลำดับ
                        </button>
                    @endif
                    <span
                        class="inline-flex items-center justify-center min-w-[2rem] h-8 px-3 rounded-full bg-white/20 text-white text-sm font-bold">
                        {{ $visibleBanners->count() }}
                    </span>
                </div>
            </div>

            @if ($visibleBanners->isEmpty())
                <div class="px-6 py-10 text-center">
                    <p class="text-sm text-gray-400">ยังไม่มีสไลด์ในหมวดแสดงผล</p>
                </div>
            @else
                {{-- Mobile --}}
                <div class="md:hidden divide-y divide-gray-200">
                    @foreach ($visibleBanners as $banner)
                        <div class="p-4 hover:bg-gray-50">
                            @if ($banner->image_path && Storage::disk('public')->exists($banner->image_path))
                                <img src="{{ Storage::url($banner->image_path) }}" alt="สไลด์"
                                    class="w-full h-32 object-cover shadow mb-3">
                            @endif
                            <div class="flex items-center gap-2 mb-2 text-xs">
                                @if ($banner->link_type === 'url')
                                    <span
                                        class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 font-medium">URL</span>
                                @elseif ($banner->link_type === 'pdf')
                                    <span
                                        class="px-2 py-0.5 rounded-full bg-orange-100 text-orange-800 font-medium">PDF</span>
                                @else
                                    <span
                                        class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-800 font-medium">ไม่มีลิงค์</span>
                                @endif
                                <span class="text-gray-400">{{ $banner->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @if ($banner->link_type === 'url' && $banner->link_url)
                                <a href="{{ $banner->link_url }}" target="_blank"
                                    class="text-xs text-blue-600 hover:text-blue-800 block truncate mb-2">{{ Str::limit($banner->link_url, 40) }}</a>
                            @elseif ($banner->link_type === 'pdf' && $banner->pdf_path)
                                <a href="{{ route('banner.pdf.view', [$banner->id, basename($banner->pdf_path)]) }}"
                                    target="_blank"
                                    class="text-xs text-orange-600 hover:text-orange-800 block truncate mb-2">{{ basename($banner->pdf_path) }}</a>
                            @endif
                            <div class="flex gap-2">
                                <button wire:click="openEditModal({{ $banner->id }})"
                                    class="flex-1 px-3 py-1.5 text-xs border border-yellow-600 text-yellow-600 rounded hover:bg-yellow-600 hover:text-white transition-colors">แก้ไข</button>
                                <button wire:click="deleteBanner({{ $banner->id }})"
                                    wire:confirm="คุณแน่ใจหรือไม่ว่าต้องการลบรูปภาพสไลด์นี้?"
                                    class="flex-1 px-3 py-1.5 text-xs border border-red-600 text-red-600 rounded hover:bg-red-600 hover:text-white transition-colors">ลบ</button>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{-- Desktop --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-green-50">
                            <tr>
                                <th
                                    class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    รูปภาพ</th>
                                <th
                                    class="w-28 px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ประเภท</th>
                                <th
                                    class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ลิงค์/ไฟล์</th>
                                <th
                                    class="w-40 px-4 lg:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($visibleBanners as $banner)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 lg:px-6 py-3">
                                        @if ($banner->image_path && Storage::disk('public')->exists($banner->image_path))
                                            <img src="{{ Storage::url($banner->image_path) }}" alt="สไลด์"
                                                class="h-40 w-72 object-cover shadow-sm">
                                        @else
                                            <div class="h-40 w-72 bg-gray-100 flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 lg:px-6 py-3 whitespace-nowrap">
                                        @if ($banner->link_type === 'none')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">ไม่มีลิงค์</span>
                                        @elseif ($banner->link_type === 'url')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">URL</span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">PDF</span>
                                        @endif
                                    </td>
                                    <td class="px-4 lg:px-6 py-3 text-sm text-gray-500">
                                        @if ($banner->link_type === 'url' && $banner->link_url)
                                            <a href="{{ $banner->link_url }}" target="_blank"
                                                class="text-blue-600 hover:text-blue-800 break-all"
                                                title="{{ $banner->link_url }}">{{ Str::limit($banner->link_url, 50) }}</a>
                                        @elseif ($banner->link_type === 'pdf' && $banner->pdf_path)
                                            <a href="{{ route('banner.pdf.view', [$banner->id, basename($banner->pdf_path)]) }}"
                                                target="_blank"
                                                class="text-orange-600 hover:text-orange-800 break-words">{{ basename($banner->pdf_path) }}</a>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 lg:px-6 py-3 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button wire:click="openEditModal({{ $banner->id }})"
                                                class="px-3 py-1.5 text-sm border border-yellow-600 text-yellow-600 rounded hover:bg-yellow-600 hover:text-white transition-colors">แก้ไข</button>
                                            <button wire:click="deleteBanner({{ $banner->id }})"
                                                wire:confirm="คุณแน่ใจหรือไม่ว่าต้องการลบรูปภาพสไลด์นี้?"
                                                class="px-3 py-1.5 text-sm border border-red-600 text-red-600 rounded hover:bg-red-600 hover:text-white transition-colors">ลบ</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- ===== ซ่อน ===== --}}
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            {{-- Section Header --}}
            <div class="bg-gray-500 px-4 sm:px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-white flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                        </path>
                    </svg>
                    <div>
                        <h4 class="text-base font-bold text-white">ซ่อน</h4>
                        <p class="text-xs text-gray-300 mt-0.5">สไลด์เหล่านี้จะไม่แสดงบนหน้าเว็บ</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if ($hiddenBanners->count() > 1)
                        <button wire:click="openSortModal(1)"
                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-white/20 hover:bg-white/30 rounded-lg transition-colors"
                            title="จัดการลำดับ">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                            </svg>
                            จัดลำดับ
                        </button>
                    @endif
                    <span
                        class="inline-flex items-center justify-center min-w-[2rem] h-8 px-3 rounded-full bg-white/20 text-white text-sm font-bold">
                        {{ $hiddenBanners->count() }}
                    </span>
                </div>
            </div>

            @if ($hiddenBanners->isEmpty())
                <div class="px-6 py-10 text-center">
                    <p class="text-sm text-gray-400">ยังไม่มีสไลด์ในหมวดซ่อน</p>
                </div>
            @else
                {{-- Mobile --}}
                <div class="md:hidden divide-y divide-gray-200">
                    @foreach ($hiddenBanners as $banner)
                        <div class="p-4 hover:bg-gray-50 opacity-60 hover:opacity-100 transition-opacity">
                            @if ($banner->image_path && Storage::disk('public')->exists($banner->image_path))
                                <img src="{{ Storage::url($banner->image_path) }}" alt="สไลด์"
                                    class="w-full h-32 object-cover shadow mb-3 grayscale">
                            @endif
                            <div class="flex items-center gap-2 mb-2 text-xs">
                                @if ($banner->link_type === 'url')
                                    <span
                                        class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 font-medium">URL</span>
                                @elseif ($banner->link_type === 'pdf')
                                    <span
                                        class="px-2 py-0.5 rounded-full bg-orange-100 text-orange-800 font-medium">PDF</span>
                                @else
                                    <span
                                        class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-800 font-medium">ไม่มีลิงค์</span>
                                @endif
                                <span class="text-gray-400">{{ $banner->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @if ($banner->link_type === 'url' && $banner->link_url)
                                <a href="{{ $banner->link_url }}" target="_blank"
                                    class="text-xs text-blue-600 hover:text-blue-800 block truncate mb-2">{{ Str::limit($banner->link_url, 40) }}</a>
                            @elseif ($banner->link_type === 'pdf' && $banner->pdf_path)
                                <a href="{{ route('banner.pdf.view', [$banner->id, basename($banner->pdf_path)]) }}"
                                    target="_blank"
                                    class="text-xs text-orange-600 hover:text-orange-800 block truncate mb-2">{{ basename($banner->pdf_path) }}</a>
                            @endif
                            <div class="flex gap-2">
                                <button wire:click="openEditModal({{ $banner->id }})"
                                    class="flex-1 px-3 py-1.5 text-xs border border-yellow-600 text-yellow-600 rounded hover:bg-yellow-600 hover:text-white transition-colors">แก้ไข</button>
                                <button wire:click="deleteBanner({{ $banner->id }})"
                                    wire:confirm="คุณแน่ใจหรือไม่ว่าต้องการลบรูปภาพสไลด์นี้?"
                                    class="flex-1 px-3 py-1.5 text-xs border border-red-600 text-red-600 rounded hover:bg-red-600 hover:text-white transition-colors">ลบ</button>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{-- Desktop --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    รูปภาพ</th>
                                <th
                                    class="w-28 px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ประเภท</th>
                                <th
                                    class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ลิงค์/ไฟล์</th>
                                <th
                                    class="w-40 px-4 lg:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($hiddenBanners as $banner)
                                <tr class="hover:bg-gray-50 opacity-60 hover:opacity-100 transition-opacity">
                                    <td class="px-4 lg:px-6 py-3">
                                        @if ($banner->image_path && Storage::disk('public')->exists($banner->image_path))
                                            <img src="{{ Storage::url($banner->image_path) }}" alt="สไลด์"
                                                class="h-40 w-72 object-cover shadow-sm grayscale hover:grayscale-0 transition-all">
                                        @else
                                            <div class="h-40 w-72 bg-gray-100 flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-300" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 lg:px-6 py-3 whitespace-nowrap">
                                        @if ($banner->link_type === 'none')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">ไม่มีลิงค์</span>
                                        @elseif ($banner->link_type === 'url')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">URL</span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">PDF</span>
                                        @endif
                                    </td>
                                    <td class="px-4 lg:px-6 py-3 text-sm text-gray-500">
                                        @if ($banner->link_type === 'url' && $banner->link_url)
                                            <a href="{{ $banner->link_url }}" target="_blank"
                                                class="text-blue-600 hover:text-blue-800 break-all"
                                                title="{{ $banner->link_url }}">{{ Str::limit($banner->link_url, 50) }}</a>
                                        @elseif ($banner->link_type === 'pdf' && $banner->pdf_path)
                                            <a href="{{ route('banner.pdf.view', [$banner->id, basename($banner->pdf_path)]) }}"
                                                target="_blank"
                                                class="text-orange-600 hover:text-orange-800 break-words">{{ basename($banner->pdf_path) }}</a>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 lg:px-6 py-3 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button wire:click="openEditModal({{ $banner->id }})"
                                                class="px-3 py-1.5 text-sm border border-yellow-600 text-yellow-600 rounded hover:bg-yellow-600 hover:text-white transition-colors">แก้ไข</button>
                                            <button wire:click="deleteBanner({{ $banner->id }})"
                                                wire:confirm="คุณแน่ใจหรือไม่ว่าต้องการลบรูปภาพสไลด์นี้?"
                                                class="px-3 py-1.5 text-sm border border-red-600 text-red-600 rounded hover:bg-red-600 hover:text-white transition-colors">ลบ</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    @endif
</div>
