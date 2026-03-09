<div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div
        class="px-4 sm:px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <h3 class="text-base sm:text-lg font-semibold text-gray-900">ตัวชี้วัด Indicators</h3>
        <div class="flex items-center gap-2 w-full sm:w-auto">
            {{-- ปุ่มจัดลำดับ --}}
            @if ($contents->where('is_hidden', false)->count() > 1)
                <x-backend.action-button color="gray" label="จัดลำดับ" action="openSortModal" title="จัดการลำดับ" />
            @endif

            {{-- ปุ่มเพิ่มหมวดหมู่ --}}
            @if ($currentParentId === null || !$hasFiles)
                <x-backend.action-button color="yellow" label="เพิ่มหมวดหมู่" action="openAddFolderModal"
                    title="เพิ่มหมวดหมู่ใหม่" />
            @endif

            {{-- ปุ่มเพิ่มไฟล์ --}}
            @if ($currentParentId !== null && !$hasFolders)
                <x-backend.action-button color="red" label="เพิ่มไฟล์" action="openAddFileModal"
                    title="เพิ่มไฟล์ PDF ใหม่" />
            @endif
        </div>
    </div>

    <!-- Table or Empty State -->
    @if ($contents->isEmpty())
        <div class="px-4 sm:px-6 py-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                </path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">ยังไม่มี Content</h3>
            <p class="mt-1 text-sm text-gray-500">เริ่มต้นโดยการเพิ่ม Content Section</p>
        </div>
    @else
        {{-- แสดง column รูปภาพก็ต่อเมื่อมีรายการที่มีรูปจริงๆ อย่างน้อย 1 รายการ --}}
        @php
            $hasAnyImage = $contents->contains(function ($item) {
                return $item->image_path && Storage::disk('public')->exists($item->image_path);
            });
        @endphp

        <!-- Mobile Card View (< md) -->
        <div class="md:hidden divide-y divide-gray-200">
            @foreach ($contents as $content)
                <div class="p-4 {{ $content->is_hidden ? 'bg-red-100' : 'hover:bg-gray-50' }}">
                    <!-- Header Row -->
                    <div class="flex items-start gap-3 mb-3">
                        {{-- แสดง thumbnail เฉพาะเมื่อมีรูปจริงๆ --}}
                        @if ($hasAnyImage && $content->image_path && Storage::disk('public')->exists($content->image_path))
                            <img src="{{ Storage::url($content->image_path) }}" alt="{{ $content->name }}"
                                class="h-12 w-16 object-cover rounded shadow flex-shrink-0">
                        @endif

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span
                                    class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-100 text-xs font-semibold text-gray-700">
                                    {{ $loop->iteration }}
                                </span>
                                {{-- Badge ชนิด (mobile) --}}
                                @if ($content->type === 'folder')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium whitespace-nowrap bg-yellow-100 text-yellow-800">
                                        หมวดหมู่
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium whitespace-nowrap bg-red-100 text-red-800">
                                        ไฟล์ PDF
                                    </span>
                                @endif
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium whitespace-nowrap {{ $content->is_hidden ? 'bg-gray-100 text-gray-600' : 'bg-green-100 text-green-800' }}">
                                    {{ $content->is_hidden ? 'ซ่อน' : 'แสดงผล' }}
                                </span>
                            </div>

                            @if ($content->type === 'folder')
                                <button type="button" wire:click="navigateToFolder({{ $content->id }})"
                                    class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline text-left break-words">
                                    {{ $content->name }}
                                </button>
                            @else
                                <div class="text-sm font-medium text-gray-900 break-words">{{ $content->name }}</div>
                                @if ($content->type === 'file' && $content->file_path)
                                    <div class="text-xs text-gray-500 mt-0.5 break-all">
                                        {{ basename($content->file_path) }}</div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-2">
                        @if ($content->type === 'folder')
                            <x-backend.action-button color="blue" label="เปิด"
                                action="navigateToFolder({{ $content->id }})" />
                        @elseif($content->file_path && Storage::disk('public')->exists($content->file_path))
                            <x-backend.action-button color="blue-link" label="ดูไฟล์" :href="Storage::url($content->file_path)" />
                        @endif
                        <x-backend.action-button color="yellow-outline" label="แก้ไข"
                            action="editNode({{ $content->id }})" />
                        <x-backend.action-button color="red-outline" label="ลบ"
                            action="deleteNode({{ $content->id }})" confirm="คุณแน่ใจหรือไม่ว่าต้องการลบรายการนี้?" />
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Desktop Table View (>= md) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full table-fixed divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="w-[5%] px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ลำดับ</th>
                        {{-- แสดง column รูปภาพเฉพาะเมื่อมีรูปจริงๆ ใน contents --}}
                        @if ($hasAnyImage)
                            <th scope="col"
                                class="hidden lg:table-cell w-[10%] px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                รูปภาพ</th>
                        @endif
                        <th scope="col"
                            class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ชื่อรายการ</th>
                        <th scope="col"
                            class="w-[8%] px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ประเภท</th>
                        <th scope="col"
                            class="w-[8%] px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            สถานะ</th>
                        <th scope="col"
                            class="hidden lg:table-cell w-[12%] px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            วันที่อัปเดตล่าสุด</th>
                        <th scope="col"
                            class="w-[20%] px-4 lg:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            จัดการ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($contents as $content)
                        <tr class="{{ $content->is_hidden ? 'bg-red-100 hover:bg-red-200' : 'hover:bg-gray-50' }}">
                            <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $loop->iteration }}
                            </td>
                            {{-- แสดง td รูปภาพเฉพาะเมื่อมีรูปจริงๆ ใน contents --}}
                            @if ($hasAnyImage)
                                <td class="hidden lg:table-cell px-6 py-4">
                                    @if ($content->image_path && Storage::disk('public')->exists($content->image_path))
                                        <img src="{{ Storage::url($content->image_path) }}" alt="{{ $content->name }}"
                                            class="h-16 w-24 object-cover rounded shadow">
                                    @else
                                        <span class="text-sm text-gray-400">-</span>
                                    @endif
                                </td>
                            @endif
                            <td class="px-4 lg:px-6 py-4">
                                <div class="overflow-hidden">
                                    @if ($content->type === 'folder')
                                        <button type="button" wire:click="navigateToFolder({{ $content->id }})"
                                            class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline truncate block w-full text-left"
                                            title="{{ $content->name }}">
                                            {{ $content->name }}
                                        </button>
                                    @else
                                        <div class="text-sm font-medium text-gray-900 truncate"
                                            title="{{ $content->name }}">{{ $content->name }}</div>
                                    @endif
                                    @if ($content->type === 'file' && $content->file_path)
                                        <div class="text-xs text-gray-500 truncate mt-0.5"
                                            title="{{ basename($content->file_path) }}">
                                            {{ basename($content->file_path) }}</div>
                                    @endif
                                </div>
                            </td>
                            {{-- Badge ชนิด (desktop) --}}
                            <td class="px-4 lg:px-6 py-4">
                                @if ($content->type === 'folder')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium whitespace-nowrap bg-yellow-100 text-yellow-800">
                                        หมวดหมู่
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium whitespace-nowrap bg-red-100 text-red-800">
                                        ไฟล์ PDF
                                    </span>
                                @endif
                            </td>
                            {{-- Badge สถานะ (desktop) --}}
                            <td class="px-4 lg:px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium whitespace-nowrap {{ $content->is_hidden ? 'bg-gray-100 text-gray-600' : 'bg-green-100 text-green-800' }}">
                                    {{ $content->is_hidden ? 'ซ่อน' : 'แสดงผล' }}
                                </span>
                            </td>
                            <td class="hidden lg:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $content->updated_at->toThaiDateFull() }}
                            </td>
                            <td class="px-4 lg:px-6 py-4 text-sm font-medium">
                                <div class="flex flex-nowrap items-center justify-end gap-2">
                                    @if ($content->type === 'folder')
                                        <x-backend.action-button color="blue" label="เปิด"
                                            action="navigateToFolder({{ $content->id }})" />
                                    @elseif($content->file_path && Storage::disk('public')->exists($content->file_path))
                                        <x-backend.action-button color="blue-link" label="ดูไฟล์" :href="Storage::url($content->file_path)" />
                                    @endif
                                    <x-backend.action-button color="yellow-outline" label="แก้ไข"
                                        action="editNode({{ $content->id }})" />
                                    <x-backend.action-button color="red-outline" label="ลบ"
                                        action="deleteNode({{ $content->id }})"
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
