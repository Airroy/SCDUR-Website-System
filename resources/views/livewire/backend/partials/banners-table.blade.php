<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="px-4 sm:px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Slider Banner</h3>
        <button wire:click="openAddModal" 
                class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            เพิ่ม Banner
        </button>
    </div>

    <!-- Table or Empty State -->
    @if($banners->isEmpty())
        <div class="px-4 sm:px-6 py-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">ยังไม่มี Banner</h3>
            <p class="mt-1 text-sm text-gray-500">เริ่มต้นโดยการเพิ่ม Slider Banner</p>
        </div>
    @else
        <!-- Mobile Card View (< md) -->
        <div class="md:hidden divide-y divide-gray-200">
            @foreach($banners as $banner)
                <div class="p-4 hover:bg-gray-50">
                    <!-- Header Row -->
                    <div class="flex items-start gap-3 mb-3">
                        <!-- Sequence Badge -->
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-sm font-semibold text-gray-700">
                                {{ $banner->sequence }}
                            </span>
                        </div>
                        
                        <!-- Image -->
                        <div class="flex-shrink-0">
                            @if($banner->image_path && Storage::disk('public')->exists($banner->image_path))
                                <img src="{{ Storage::url($banner->image_path) }}" alt="Banner" class="h-16 w-24 object-cover rounded shadow">
                            @else
                                <div class="h-16 w-24 bg-gray-200 rounded flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Type Badge -->
                        <div class="flex-1 min-w-0">
                            @if($banner->link_type === 'none')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    ไม่มีลิงค์
                                </span>
                            @elseif($banner->link_type === 'url')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mb-1">
                                    URL
                                </span>
                                @if($banner->link_url)
                                    <a href="{{ $banner->link_url }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800 block truncate">
                                        {{ Str::limit($banner->link_url, 30) }}
                                    </a>
                                @endif
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 mb-1">
                                    PDF
                                </span>
                                @if($banner->pdf_path)
                                    <a href="{{ Storage::url($banner->pdf_path) }}" target="_blank" class="text-xs text-orange-600 hover:text-orange-800 block truncate">
                                        {{ $banner->pdf_name ?? 'ดูไฟล์ PDF' }}
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        <button 
                            wire:click="openEditModal({{ $banner->id }})"
                            class="flex-1 px-3 py-1.5 text-xs border border-yellow-600 text-yellow-600 rounded hover:bg-yellow-600 hover:text-white transition-colors duration-200"
                        >
                            แก้ไข
                        </button>
                        <button 
                            wire:click="deleteBanner({{ $banner->id }})"
                            wire:confirm="คุณแน่ใจหรือไม่ว่าต้องการลบ Banner นี้?"
                            class="flex-1 px-3 py-1.5 text-xs border border-red-600 text-red-600 rounded hover:bg-red-600 hover:text-white transition-colors duration-200"
                        >
                            ลบ
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Desktop Table View (>= md) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="w-20 px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ลำดับ</th>
                        <th scope="col" class="w-32 px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">รูปภาพ</th>
                        <th scope="col" class="w-28 px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ประเภท</th>
                        <th scope="col" class="hidden lg:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ลิงค์/ไฟล์</th>
                        <th scope="col" class="w-48 px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($banners as $banner)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $banner->sequence }}
                            </td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                @if($banner->image_path && Storage::disk('public')->exists($banner->image_path))
                                    <img src="{{ Storage::url($banner->image_path) }}" alt="Banner" class="h-12 w-20 object-cover rounded shadow">
                                @else
                                    <div class="h-12 w-20 bg-gray-200 rounded flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                @if($banner->link_type === 'none')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        ไม่มีลิงค์
                                    </span>
                                @elseif($banner->link_type === 'url')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        URL
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        PDF
                                    </span>
                                @endif
                            </td>
                            <td class="hidden lg:table-cell px-6 py-4 text-sm text-gray-500">
                                @if($banner->link_type === 'url' && $banner->link_url)
                                    <a href="{{ $banner->link_url }}" target="_blank" class="text-blue-600 hover:text-blue-800 truncate block max-w-sm" title="{{ $banner->link_url }}">
                                        {{ Str::limit($banner->link_url, 50) }}
                                    </a>
                                @elseif($banner->link_type === 'pdf' && $banner->pdf_path)
                                    <a href="{{ Storage::url($banner->pdf_path) }}" target="_blank" class="text-orange-600 hover:text-orange-800 truncate block max-w-sm" title="{{ $banner->pdf_name ?? 'ดูไฟล์ PDF' }}">
                                        {{ $banner->pdf_name ?? 'ดูไฟล์ PDF' }}
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <button 
                                        wire:click="openEditModal({{ $banner->id }})"
                                        class="px-3 py-1.5 text-sm border border-yellow-600 text-yellow-600 rounded hover:bg-yellow-600 hover:text-white transition-colors duration-200"
                                    >
                                        แก้ไข
                                    </button>
                                    <button 
                                        wire:click="deleteBanner({{ $banner->id }})"
                                        wire:confirm="คุณแน่ใจหรือไม่ว่าต้องการลบ Banner นี้?"
                                        class="px-3 py-1.5 text-sm border border-red-600 text-red-600 rounded hover:bg-red-600 hover:text-white transition-colors duration-200"
                                    >
                                        ลบ
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>