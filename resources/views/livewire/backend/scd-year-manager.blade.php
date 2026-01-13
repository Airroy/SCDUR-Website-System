<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Grid of Year Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <!-- Add New Year Card -->
        <x-admin::card-add 
            wire:click="openAddModal"
            title="เพิ่มปี SCD ใหม่"
            subtitle="คลิกเพื่อสร้างปีใหม่"
            color="red"
        />

        <!-- Existing Year Cards -->
        @foreach($years as $year)
            <x-admin::card-item 
                :title="'SCD ' . $year->year"
                x-data="{ menuOpen: false }"
                class="relative hover:border-red-400"
            >
                <x-slot:icon>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </x-slot:icon>

                <x-slot:content>
                    <!-- Status Text -->
                    @if($year->is_published)
                        <span class="text-sm font-medium text-green-600">เปิดแสดงผล</span>
                    @else
                        <span class="text-sm font-medium text-yellow-600">แบบร่าง</span>
                    @endif
                </x-slot:content>

                <x-slot:actions>
                    <!-- Three-dot Menu -->
                    <button @click="menuOpen = !menuOpen" 
                            type="button"
                            class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="menuOpen" 
                         @click.outside="menuOpen = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-4 top-16 z-50 w-56 bg-white rounded-lg shadow-lg border border-gray-200"
                         style="display: none;">
                        <ul class="py-2 text-base">
                            <li>
                                <a href="{{ route('admin.years.manage', $year->id) }}"
                                   class="block px-4 py-3 hover:bg-gray-50 text-gray-700">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    จัดการข้อมูลภายในปี
                                </a>
                            </li>
                            <li>
                                <button wire:click="openEditModal({{ $year->id }})" 
                                        @click="menuOpen = false"
                                        class="w-full text-left px-4 py-3 hover:bg-gray-50 text-gray-700">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    แก้ไขข้อมูล
                                </button>
                            </li>
                            <li>
                                <button wire:click="togglePublish({{ $year->id }})" 
                                        @click="menuOpen = false"
                                        class="w-full text-left px-4 py-3 hover:bg-gray-50 text-gray-700">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($year->is_published)
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        @endif
                                    </svg>
                                    {{ $year->is_published ? 'ซ่อนจากการแสดงผล' : 'เปิดแสดงผล' }}
                                </button>
                            </li>
                            <li class="border-t border-gray-200 mt-1 pt-1">
                                <button @click="$dispatch('confirm', {
                                            message: 'ต้องการลบปี SCD {{ $year->year }} หรือไม่?<br><span class=\'text-sm text-gray-500\'>ข้อมูลทั้งหมดภายในปีนี้จะถูกลบด้วย</span>',
                                            callback: () => $wire.delete({{ $year->id }})
                                        }); menuOpen = false"
                                        class="w-full text-left px-4 py-3 hover:bg-red-50 text-red-600">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    ลบปี SCD
                                </button>
                            </li>
                        </ul>
                    </div>
                </x-slot:actions>
            </x-admin::card-item>
        @endforeach
        </div>

        <!-- Modal for Add/Edit -->
        @if($showModal)
            <x-admin::modal 
                title="{{ $editMode ? 'แก้ไขข้อมูลปี' : 'เพิ่มปี SCD ใหม่' }}" 
                maxWidth="lg"
            >
            <form wire:submit.prevent="save" class="p-6">
                <div class="space-y-4">
                    <!-- Year Input -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            ปี SCD (ค.ศ.) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               wire:model="year"
                               placeholder="เช่น 2026"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                               required>
                        @error('year')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">กรอกปีเป็นตัวเลข 4 หลัก (ค.ศ.)</p>
                    </div>

                    <!-- Created Date Input -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            วันที่สร้าง <span class="text-red-500">*</span>
                        </label>
                        @if($editMode && $created_date)
                            <p class="text-xs text-gray-500 mb-2">
                                วันที่สร้างก่อนหน้า: <span class="font-semibold">{{ \Carbon\Carbon::parse($created_date)->format('d/m/Y') }}</span>
                            </p>
                        @endif
                        <input type="date" 
                               wire:model="created_date"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                               required>
                        @error('created_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Published Toggle -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg" x-data="{ checked: @entangle('is_published') }">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">
                                เผยแพร่
                            </label>
                            <p class="text-xs text-gray-500 mt-1">เปิดเพื่อแสดงข้อมูลของปีนี้ในหน้าเว็บไซต์</p>
                        </div>
                        <button type="button"
                                @click="checked = !checked"
                                :class="checked ? 'bg-green-500' : 'bg-gray-300'"
                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors">
                            <span :class="checked ? 'translate-x-6' : 'translate-x-1'" class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                        </button>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="mt-6 flex justify-end gap-3">
                    <button 
                        type="button" 
                        wire:click="closeModal"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition"
                    >
                        ยกเลิก
                    </button>
                    <button 
                        type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove wire:target="save">
                            {{ $editMode ? 'บันทึกการแก้ไข' : 'เพิ่มปี' }}
                        </span>
                        <span wire:loading wire:target="save">
                            กำลังบันทึก...
                        </span>
                    </button>
                </div>
            </form>
        </x-admin::modal>
        @endif
    </div>
</div>
