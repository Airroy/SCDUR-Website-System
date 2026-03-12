<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">
            @if (count($breadcrumbs) > 0)
                {{ $breadcrumbs[count($breadcrumbs) - 1]['name'] }}
            @else
                {{ $category === 'announcement' ? 'ประกาศ' : 'คำสั่ง' }}
            @endif
        </h3>
        <div class="flex items-center gap-2">
            @if ($items->where('is_hidden', false)->count() > 1)
                <x-backend.action-button color="gray" label="จัดลำดับ" action="openSortModal" title="จัดการลำดับ" />
            @endif
            @livewire(
                'backend.announcement-manager',
                [
                    'year' => $selectedYear,
                    'categoryGroup' => $category,
                    'parentId' => $currentParentId,
                    'hasFilesInParent' => $hasFiles,
                    'hasFoldersInParent' => $hasFolders,
                ],
                key('announcement-manager-' . $selectedYear->id . '-' . $category . '-' . ($currentParentId ?? 'root'))
            )
        </div>
    </div>

    <!-- Table or Empty State -->
    @if ($items->isEmpty())
        <div class="px-6 py-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">ยังไม่มีรายการ</h3>
            <p class="mt-1 text-sm text-gray-500">เริ่มต้นโดยการเพิ่มหมวดหมู่หรือไฟล์</p>
        </div>
    @else
        {{-- ========== มือถือเท่านั้น ========== --}}
        <div class="md:hidden divide-y divide-gray-100">
            @foreach ($items as $item)
                <div class="px-4 py-3 {{ $item->is_hidden ? 'bg-red-100' : '' }}">
                    <div class="flex items-start gap-2 mb-2">
                        <span
                            class="text-xs text-gray-400 font-medium mt-0.5 w-4 flex-shrink-0">{{ $loop->iteration }}</span>
                        <div class="flex-1 min-w-0">
                            @if ($item->type === 'folder')
                                <button wire:click="navigateToFolder({{ $item->id }})"
                                    class="text-sm font-medium text-blue-600 hover:underline text-left w-full">
                                    {{ $item->name }}
                                </button>
                            @else
                                <p class="text-sm font-medium text-gray-900">{{ $item->name }}</p>
                                @if ($item->file_path)
                                    <p class="text-xs text-gray-400 truncate">{{ basename($item->file_path) }}</p>
                                @endif
                            @endif
                        </div>
                        <div class="flex flex-col items-end gap-1 flex-shrink-0">
                            @if ($item->type === 'folder')
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">หมวดหมู่</span>
                            @else
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">PDF</span>
                            @endif
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $item->is_hidden ? 'bg-gray-100 text-gray-600' : 'bg-green-100 text-green-800' }}">
                                {{ $item->is_hidden ? 'ซ่อน' : 'แสดงผล' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 pl-6">
                        @if ($item->type === 'folder')
                            <x-backend.action-button color="blue" label="เปิดโฟลเดอร์"
                                action="navigateToFolder({{ $item->id }})" />
                        @elseif ($item->file_path)
                            <x-backend.action-button color="blue-link" label="ดูไฟล์" :href="Storage::url($item->file_path)"
                                target="_blank" />
                        @endif
                        <x-backend.action-button color="yellow-outline" label="แก้ไข"
                            action="$dispatch('openEditAnnouncementModal', { announcementId: {{ $item->id }} })" />
                        <x-backend.action-button color="red-outline" label="ลบ"
                            action="$dispatch('deleteAnnouncement', { announcementId: {{ $item->id }} })"
                            confirm="คุณแน่ใจหรือไม่ว่าต้องการลบรายการนี้?" />
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ========== คอม — ของเดิมทั้งหมด ไม่แตะเลย ========== --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="w-24 px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ลำดับ</th>
                        <th
                            class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ชื่อรายการ</th>
                        <th
                            class="hidden md:table-cell w-28 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ประเภท</th>
                        <th
                            class="hidden md:table-cell w-28 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            สถานะ</th>
                        <th
                            class="hidden md:table-cell w-36 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            วันที่อัปเดตล่าสุด</th>
                        <th
                            class="w-80 px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            จัดการ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($items as $item)
                        <tr class="{{ $item->is_hidden ? 'bg-red-100 hover:bg-red-200' : 'hover:bg-gray-50' }}">
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-3 sm:px-6 py-4">
                                <div class="max-w-xs overflow-hidden">
                                    @if ($item->type === 'folder')
                                        <button wire:click="navigateToFolder({{ $item->id }})"
                                            class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline truncate block w-full text-left"
                                            title="{{ $item->name }}">
                                            {{ $item->name }}
                                        </button>
                                    @else
                                        <div class="text-sm font-medium text-gray-900 truncate w-full"
                                            title="{{ $item->name }}">
                                            {{ $item->name }}
                                        </div>
                                        @if ($item->file_path)
                                            <div class="text-xs text-gray-500 truncate w-full"
                                                title="{{ basename($item->file_path) }}">
                                                {{ basename($item->file_path) }}
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td class="hidden md:table-cell px-6 py-4">
                                @if ($item->type === 'folder')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">หมวดหมู่</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">ไฟล์
                                        PDF</span>
                                @endif
                            </td>
                            <td class="hidden md:table-cell px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->is_hidden ? 'bg-gray-100 text-gray-600' : 'bg-green-100 text-green-800' }}">
                                    {{ $item->is_hidden ? 'ซ่อน' : 'แสดงผล' }}
                                </span>
                            </td>
                            <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item->updated_at->toThaiDateFull() }}
                            </td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                <div class="flex flex-wrap items-center gap-1 sm:gap-2">
                                    @if ($item->type === 'folder')
                                        <x-backend.action-button color="blue" label="เปิด"
                                            action="navigateToFolder({{ $item->id }})" />
                                    @elseif ($item->file_path)
                                        <x-backend.action-button color="blue-link" label="ดูไฟล์" :href="Storage::url($item->file_path)" />
                                    @endif
                                    <x-backend.action-button color="yellow-outline" label="แก้ไข"
                                        action="$dispatch('openEditAnnouncementModal', { announcementId: {{ $item->id }} })" />
                                    <x-backend.action-button color="red-outline" label="ลบ"
                                        action="$dispatch('deleteAnnouncement', { announcementId: {{ $item->id }} })"
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
