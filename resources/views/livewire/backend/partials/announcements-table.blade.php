<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">
            @if(count($breadcrumbs) > 0)
                {{ $breadcrumbs[count($breadcrumbs) - 1]['name'] }}
            @else
                {{ $category === 'announcement' ? 'ประกาศ' : 'คำสั่ง' }}
            @endif
        </h3>
        @livewire('backend.announcement-manager', [
            'year' => $selectedYear, 
            'categoryGroup' => $category,
            'parentId' => $currentParentId,
            'hasFilesInParent' => $hasFiles,
            'hasFoldersInParent' => $hasFolders
        ], key('announcement-manager-'.$selectedYear->id.'-'.$category.'-'.($currentParentId ?? 'root')))
    </div>

    <!-- Table or Empty State -->
    @if($items->isEmpty())
        <div class="px-6 py-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">ยังไม่มีรายการ</h3>
            <p class="mt-1 text-sm text-gray-500">เริ่มต้นโดยการเพิ่มหมวดหมู่หรือไฟล์</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="w-24 px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ลำดับ</th>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ชื่อรายการ</th>
                        <th scope="col" class="hidden md:table-cell w-40 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ชนิด</th>
                        <th scope="col" class="w-80 px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($items as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $item->sequence }}
                            </td>
                            <td class="px-3 sm:px-6 py-4">
                                <div class="max-w-xs overflow-hidden">
                                    @if($item->type === 'folder')
                                        <button 
                                            wire:click="navigateToFolder({{ $item->id }})"
                                            class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline truncate block w-full text-left"
                                            title="{{ $item->name }}"
                                        >
                                            {{ $item->name }}
                                        </button>
                                    @else
                                        <div class="text-sm font-medium text-gray-900 truncate w-full" title="{{ $item->name }}">{{ $item->name }}</div>
                                        @if($item->file_path)
                                            <div class="text-xs text-gray-500 truncate w-full" title="{{ basename($item->file_path) }}">{{ basename($item->file_path) }}</div>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td class="hidden md:table-cell px-6 py-4">
                                @if($item->type === 'folder')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        หมวดหมู่
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        ไฟล์ PDF
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                <div class="flex flex-wrap items-center gap-1 sm:gap-2">
                                    @if($item->type === 'folder')
                                        <button 
                                            wire:click="navigateToFolder({{ $item->id }})"
                                            class="px-2 sm:px-3 py-1 sm:py-1.5 text-xs sm:text-sm border border-blue-600 text-blue-600 rounded hover:bg-blue-600 hover:text-white transition-colors duration-200"
                                        >
                                            เปิด
                                        </button>
                                    @else
                                        @if($item->file_path)
                                            <a 
                                                href="{{ Storage::url($item->file_path) }}" 
                                                target="_blank"
                                                class="px-2 sm:px-3 py-1 sm:py-1.5 text-xs sm:text-sm border border-blue-600 text-blue-600 rounded hover:bg-blue-600 hover:text-white transition-colors duration-200"
                                            >
                                                ดูไฟล์
                                            </a>
                                        @endif
                                    @endif
                                    <button 
                                        wire:click="$dispatch('openEditAnnouncementModal', { announcementId: {{ $item->id }} })"
                                        class="px-2 sm:px-3 py-1 sm:py-1.5 text-xs sm:text-sm border border-yellow-600 text-yellow-600 rounded hover:bg-yellow-600 hover:text-white transition-colors duration-200"
                                    >
                                        แก้ไข
                                    </button>
                                    <button 
                                        wire:click="$dispatch('deleteAnnouncement', { announcementId: {{ $item->id }} })"
                                        wire:confirm="คุณแน่ใจหรือไม่ว่าต้องการลบรายการนี้?"
                                        class="px-2 sm:px-3 py-1 sm:py-1.5 text-xs sm:text-sm border border-red-600 text-red-600 rounded hover:bg-red-600 hover:text-white transition-colors duration-200"
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
