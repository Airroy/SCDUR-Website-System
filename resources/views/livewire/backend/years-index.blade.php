<div class="p-4 sm:p-6">
    <!-- Page Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">จัดการปี SCD</h1>
            <p class="mt-1 text-sm text-gray-600">เพิ่ม แก้ไข และจัดการข้อมูลปี SCD</p>
        </div>
        <button wire:click="$toggle('showCreateModal')" 
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition shadow-lg hover:shadow-xl whitespace-nowrap">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>เพิ่มปีใหม่</span>
        </button>
    </div>

    <!-- Years List -->
    <!-- Years List -->
    @if($years->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="w-36 px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">ปี</th>
                            <th class="hidden sm:table-cell w-44 px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">วันที่</th>
                            <th class="w-40 px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">สถานะ</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($years as $year)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 sm:px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">ปี {{ $year->year }}</td>
                            <td class="hidden sm:table-cell px-4 sm:px-6 py-4 text-sm text-gray-600 whitespace-nowrap">{{ $year->created_date->toThaiDateFull() }}</td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <button wire:click="togglePublish({{ $year->id }})" 
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition {{ $year->is_published ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                    {{ $year->is_published ? 'เผยแพร่แล้ว' : 'ฉบับร่าง' }}
                                </button>
                            </td>
                            <td class="px-3 sm:px-6 py-4 text-left whitespace-nowrap">
                                <!-- สำหรับมือถือเท่านั้น: แสดงปุ่มแนวตั้งขนาดเท่ากัน -->
                                <div class="flex flex-col gap-2 sm:hidden">
                                    <a href="{{ route('admin.reports.index', ['year' => $year->year]) }}" 
                                       class="w-full px-3 py-2 text-xs text-center border border-blue-600 text-blue-600 rounded hover:bg-blue-600 hover:text-white transition-colors duration-200">
                                        จัดการข้อมูล
                                    </a>
                                    <button wire:click="editYear({{ $year->id }})"
                                            class="w-full px-3 py-2 text-xs border border-yellow-600 text-yellow-600 rounded hover:bg-yellow-600 hover:text-white transition-colors duration-200">
                                        แก้ไข
                                    </button>
                                    <button wire:click="deleteYear({{ $year->id }})" 
                                            wire:confirm="ต้องการลบปี {{ $year->year }} ใช่หรือไม่?"
                                            class="w-full px-3 py-2 text-xs border border-red-600 text-red-600 rounded hover:bg-red-600 hover:text-white transition-colors duration-200">
                                        ลบ
                                    </button>
                                </div>
                                
                                <!-- สำหรับคอมเท่านั้น: โค้ดเดิมไม่แก้ -->
                                <div class="hidden sm:flex flex-wrap items-center gap-1 sm:gap-2">
                                    <a href="{{ route('admin.reports.index', ['year' => $year->year]) }}" 
                                       class="px-2 sm:px-3 py-1 sm:py-1.5 text-xs sm:text-sm border border-blue-600 text-blue-600 rounded hover:bg-blue-600 hover:text-white transition-colors duration-200">
                                        จัดการข้อมูล
                                    </a>
                                    <button wire:click="editYear({{ $year->id }})"
                                            class="px-2 sm:px-3 py-1 sm:py-1.5 text-xs sm:text-sm border border-yellow-600 text-yellow-600 rounded hover:bg-yellow-600 hover:text-white transition-colors duration-200">
                                        แก้ไข
                                    </button>
                                    <button wire:click="deleteYear({{ $year->id }})" 
                                            wire:confirm="ต้องการลบปี {{ $year->year }} ใช่หรือไม่?"
                                            class="px-2 sm:px-3 py-1 sm:py-1.5 text-xs sm:text-sm border border-red-600 text-red-600 rounded hover:bg-red-600 hover:text-white transition-colors duration-200">
                                        ลบ
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 text-center py-12 px-4">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">ยังไม่มีข้อมูลปี</h3>
            <p class="mt-1 text-sm text-gray-500">เริ่มต้นโดยการคลิกปุ่ม "เพิ่มปีใหม่" ด้านบน</p>
        </div>
    @endif

    <!-- Create Modal -->
    <x-backend.modal :show="$showCreateModal" closeEvent="showCreateModal">
        <x-backend.modal-form wire:submit.prevent="createYear" title="เพิ่มปีใหม่" submitLabel="บันทึก" closeEvent="showCreateModal">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ปี ค.ศ. <span class="text-red-600">*</span></label>
                    <input type="number" wire:model="year" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="เช่น 2026" min="1900" max="2200" required>
                    @error('year') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">วันที่ <span class="text-red-600">*</span></label>
                    <input type="date" wire:model="created_date" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                           required>
                    @error('created_date') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>
                
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox" wire:model="is_published" id="is_published_create"
                               class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                    </div>
                    <div class="ml-3">
                        <label for="is_published_create" class="text-sm font-medium text-gray-700">แสดงหน้าเว็บไซต์</label>
                        <p class="text-xs text-gray-500">หากไม่ติ๊กจะเป็นแบบร่าง (ไม่แสดงหน้าเว็บ)</p>
                    </div>
                </div>
            </div>
        </x-backend.modal-form>
    </x-backend.modal>

    <!-- Edit Modal -->
    <x-backend.modal :show="$showEditModal" closeEvent="showEditModal">
        <x-backend.modal-form wire:submit.prevent="updateYear" title="แก้ไขข้อมูลปี" submitLabel="อัพเดท" closeEvent="showEditModal">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ปี ค.ศ. <span class="text-red-600">*</span></label>
                    <input type="number" wire:model="year" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="เช่น 2026" min="1900" max="2200" required>
                    @error('year') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">วันที่ <span class="text-red-600">*</span></label>
                    <input type="date" wire:model="created_date" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                           required>
                    @error('created_date') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>
                
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input type="checkbox" wire:model="is_published" id="is_published_edit"
                               class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                    </div>
                    <div class="ml-3">
                        <label for="is_published_edit" class="text-sm font-medium text-gray-700">แสดงหน้าเว็บไซต์</label>
                        <p class="text-xs text-gray-500">หากไม่ติ๊กจะเป็นแบบร่าง (ไม่แสดงหน้าเว็บ)</p>
                    </div>
                </div>
            </div>
        </x-backend.modal-form>
    </x-backend.modal>
</div>