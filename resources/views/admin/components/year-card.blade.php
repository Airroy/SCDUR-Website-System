@props(['year'])

<div class="bg-white block p-6 border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow relative hover:border-red-400" x-data="{ menuOpen: false }">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-4xl font-semibold text-gray-800">SCD {{ $year->year }}</h3>
        <button @click="menuOpen = !menuOpen" 
                type="button"
                class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
            </svg>
        </button>
    </div>
    
    <p class="text-lg text-gray-600 mb-4">
        สร้างเมื่อ: {{ \Carbon\Carbon::parse($year->created_date)->format('d/m/Y') }}
    </p>
    
    <!-- Status -->
    @if($year->is_published)
        <div class="flex items-center p-3 bg-green-50 rounded-lg border border-green-200">
            <svg class="w-6 h-6 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="text-base font-medium text-green-800">เปิดแสดงผล</span>
        </div>
    @else
        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
            <svg class="w-6 h-6 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <span class="text-base font-medium text-gray-600">แบบร่าง</span>
        </div>
    @endif
    
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
</div>
