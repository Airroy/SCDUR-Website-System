<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">Content Section</h3>
        @livewire('backend.content-section-manager', [
            'year' => $selectedYear,
            'parentId' => $currentParentId ?? null,
            'hasFilesInParent' => $hasFiles,
            'hasFoldersInParent' => $hasFolders,
        ], key('content-add-'.$selectedYear->id.'-'.($currentParentId ?? 'root')))
    </div>

    <!-- Table or Empty State -->
    @if($contents->isEmpty())
        <div class="px-6 py-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">ยังไม่มี Content</h3>
            <p class="mt-1 text-sm text-gray-500">เริ่มต้นโดยการเพิ่ม Content Section</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="w-24 px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ลำดับ</th>
                        <th scope="col" class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ชื่อรายการ</th>
                        <th scope="col" class="hidden lg:table-cell w-48 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">รูปภาพ</th>
                        <th scope="col" class="hidden md:table-cell w-36 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ชนิด</th>
                        <th scope="col" class="w-80 px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($contents as $content)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $content->sequence }}
                            </td>
                            <td class="px-3 sm:px-6 py-4">
                                <div class="max-w-xs overflow-hidden">
                                    @if($content->type === 'folder')
                                        <button 
                                            wire:click="$dispatch('viewFolder', { folderId: {{ $content->id }} })"
                                            class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline truncate block w-full text-left"
                                            title="{{ $content->name }}"
                                        >
                                            {{ $content->name }}
                                        </button>
                                    @else
                                        <div class="text-sm font-medium text-gray-900 truncate w-full" title="{{ $content->name }}">{{ $content->name }}</div>
                                    @endif
                                    @if($content->type === 'file' && $content->file_path)
                                        <div class="text-xs text-gray-500 truncate w-full" title="{{ basename($content->file_path) }}">{{ basename($content->file_path) }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="hidden lg:table-cell px-6 py-4">
                                @if($content->image_path && Storage::disk('public')->exists($content->image_path))
                                    <img src="{{ Storage::url($content->image_path) }}" alt="{{ $content->name }}" class="h-16 w-24 object-cover rounded shadow">
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="hidden md:table-cell px-6 py-4 text-sm text-gray-500">
                                {{ $content->type === 'folder' ? 'หมวดหมู่' : 'ไฟล์' }}
                            </td>
                            <td class="px-3 sm:px-6 py-4 text-sm font-medium">
                                <div class="flex flex-wrap items-center gap-1 sm:gap-2">
                                    @if($content->type === 'folder')
                                        <button 
                                            wire:click="$dispatch('viewFolder', { folderId: {{ $content->id }} })"
                                            class="px-2 sm:px-3 py-1 sm:py-1.5 text-xs sm:text-sm text-blue-600 hover:text-white hover:bg-blue-600 border border-blue-600 rounded transition-colors duration-200"
                                        >
                                            เปิด
                                        </button>
                                    @elseif($content->file_path && Storage::disk('public')->exists($content->file_path))
                                        <a 
                                            href="{{ Storage::url($content->file_path) }}" 
                                            target="_blank"
                                            class="px-2 sm:px-3 py-1 sm:py-1.5 text-xs sm:text-sm text-blue-600 hover:text-white hover:bg-blue-600 border border-blue-600 rounded transition-colors duration-200"
                                        >
                                            ดูไฟล์
                                        </a>
                                    @endif
                                    <button 
                                        wire:click="$dispatch('openEditContentModal', { contentId: {{ $content->id }} })"
                                        class="px-2 sm:px-3 py-1 sm:py-1.5 text-xs sm:text-sm text-yellow-600 hover:text-white hover:bg-yellow-600 border border-yellow-600 rounded transition-colors duration-200"
                                    >
                                        แก้ไข
                                    </button>
                                    <button 
                                        wire:click="$dispatch('deleteContent', { contentId: {{ $content->id }} })"
                                        wire:confirm="คุณแน่ใจหรือไม่ว่าต้องการลบรายการนี้?"
                                        class="px-2 sm:px-3 py-1 sm:py-1.5 text-xs sm:text-sm text-red-600 hover:text-white hover:bg-red-600 border border-red-600 rounded transition-colors duration-200"
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
