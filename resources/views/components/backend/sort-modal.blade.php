{{-- 
    Sort Modal Component - จัดการลำดับรายการ
    
    Props:
    - show: boolean - แสดง/ซ่อน modal
    - title: string - หัวข้อ modal
    - items: array - รายการที่จะจัดลำดับ [['id' => ..., 'label' => ..., 'sublabel' => ..., 'image' => ...], ...]
    - maxWidth: string - ขนาดสูงสุดของ modal (sm, md, lg, xl, 2xl)
    
    Usage:
    <x-backend.sort-modal 
        :show="$showSortModal" 
        :items="$sortableItems" 
        title="จัดการลำดับ"
    />
    
    Requires Livewire method: saveSortOrder(array $orderedIds)
--}}

@props(['show' => false, 'title' => 'จัดการลำดับ', 'items' => [], 'maxWidth' => 'lg'])

@php
    $maxWidthClass = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
    ][$maxWidth];
@endphp

<div>
@if ($show)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
        aria-labelledby="sort-modal-title" role="dialog" aria-modal="true"
        x-data="sortableList({{ json_encode($items) }})" x-cloak>

        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <!-- Modal panel — fixed max-height, flex column -->
        <div class="relative bg-white rounded-lg text-left shadow-xl transform transition-all w-full {{ $maxWidthClass }} flex flex-col"
            style="max-height: calc(100vh - 2rem);">

            <!-- Header (fixed) -->
            <div class="flex-shrink-0 px-6 py-4 bg-white border-b border-gray-200 rounded-t-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 id="sort-modal-title" class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                            <p class="text-xs text-gray-500">ลากรายการหรือกดลูกศรเพื่อเปลี่ยนลำดับ</p>
                        </div>
                    </div>
                    <button wire:click="$set('showSortModal', false)"
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Sortable List (scrollable) -->
            <div class="flex-1 overflow-y-auto px-6 py-4 min-h-0">
                <template x-if="items.length === 0">
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                            </path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">ไม่มีรายการที่จะจัดลำดับ</p>
                    </div>
                </template>

                <div class="space-y-2" x-ref="sortableContainer">
                    <template x-for="(item, index) in items" :key="item.id">
                        <div class="group flex items-center gap-3 p-3 bg-white border-2 rounded-lg transition-all duration-200 cursor-grab active:cursor-grabbing"
                            :class="{
                                'border-red-300 bg-red-50 shadow-md scale-[1.02]': dragIndex === index,
                                'border-red-200 bg-red-50/50': dragOverIndex === index && dragIndex !== index,
                                'border-gray-200 hover:border-gray-300 hover:shadow-sm': dragIndex !== index && dragOverIndex !== index
                            }"
                            draggable="true"
                            @dragstart="dragStart(index, $event)"
                            @dragover.prevent="dragOver(index)"
                            @dragenter.prevent="dragOver(index)"
                            @dragleave="dragLeave(index)"
                            @drop.prevent="drop(index)"
                            @dragend="dragEnd()">

                            <!-- Drag Handle -->
                            <div class="flex-shrink-0 text-gray-400 group-hover:text-gray-600 cursor-grab active:cursor-grabbing">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm0 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm0 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm8-12a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm0 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm0 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0z" />
                                </svg>
                            </div>

                            <!-- Sequence Number -->
                            <div class="flex-shrink-0 w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                <span class="text-sm font-bold text-gray-700" x-text="index + 1"></span>
                            </div>

                            <!-- Thumbnail (optional) -->
                            <template x-if="item.image">
                                <img :src="item.image" :alt="item.label"
                                    class="flex-shrink-0 h-10 w-14 object-cover rounded shadow-sm">
                            </template>

                            <!-- Label -->
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate" x-text="item.label"></p>
                                <template x-if="item.sublabel">
                                    <p class="text-xs text-gray-500 truncate" x-text="item.sublabel"></p>
                                </template>
                            </div>

                            <!-- Up/Down Buttons -->
                            <div class="flex-shrink-0 flex flex-col gap-0.5">
                                <button type="button" @click="moveUp(index)"
                                    class="p-1 rounded transition-colors"
                                    :class="index === 0 ? 'text-gray-300 cursor-not-allowed' : 'text-gray-500 hover:text-red-600 hover:bg-red-50'"
                                    :disabled="index === 0" title="เลื่อนขึ้น">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                </button>
                                <button type="button" @click="moveDown(index)"
                                    class="p-1 rounded transition-colors"
                                    :class="index === items.length - 1 ? 'text-gray-300 cursor-not-allowed' : 'text-gray-500 hover:text-red-600 hover:bg-red-50'"
                                    :disabled="index === items.length - 1" title="เลื่อนลง">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Footer (fixed) -->
            <div class="flex-shrink-0 bg-gray-50 px-6 py-4 border-t border-gray-200 rounded-b-lg flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <button type="button" wire:click="$set('showSortModal', false)"
                    class="w-full sm:w-auto inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                    ยกเลิก
                </button>
                <button type="button" @click="save()"
                    class="w-full sm:w-auto inline-flex justify-center items-center rounded-lg border border-transparent shadow-sm px-4 py-2.5 bg-red-600 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                    wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        wire:loading.remove wire:target="saveSortOrder">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"
                        wire:loading wire:target="saveSortOrder" style="display: none;">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span wire:loading.remove wire:target="saveSortOrder">บันทึกลำดับ</span>
                    <span wire:loading wire:target="saveSortOrder" style="display: none;">กำลังบันทึก...</span>
                </button>
            </div>
        </div>
    </div>
@endif
</div>

@once
    @push('scripts')
        <script>
            window.sortableList = function(initialItems) {
                return {
                    items: JSON.parse(JSON.stringify(initialItems)),
                    dragIndex: null,
                    dragOverIndex: null,

                    dragStart(index, event) {
                        this.dragIndex = index;
                        event.dataTransfer.effectAllowed = 'move';
                        event.dataTransfer.setData('text/plain', index);
                        event.target.style.opacity = '0.7';
                    },

                    dragOver(index) {
                        if (this.dragIndex === null || this.dragIndex === index) return;
                        this.dragOverIndex = index;
                    },

                    dragLeave(index) {
                        if (this.dragOverIndex === index) {
                            this.dragOverIndex = null;
                        }
                    },

                    drop(index) {
                        if (this.dragIndex === null || this.dragIndex === index) {
                            this.dragEnd();
                            return;
                        }
                        const item = this.items.splice(this.dragIndex, 1)[0];
                        this.items.splice(index, 0, item);
                        this.dragEnd();
                    },

                    dragEnd() {
                        this.dragIndex = null;
                        this.dragOverIndex = null;
                        document.querySelectorAll('[draggable="true"]').forEach(el => {
                            el.style.opacity = '1';
                        });
                    },

                    moveUp(index) {
                        if (index === 0) return;
                        const temp = this.items[index];
                        this.items[index] = this.items[index - 1];
                        this.items[index - 1] = temp;
                        this.items = [...this.items];
                    },

                    moveDown(index) {
                        if (index >= this.items.length - 1) return;
                        const temp = this.items[index];
                        this.items[index] = this.items[index + 1];
                        this.items[index + 1] = temp;
                        this.items = [...this.items];
                    },

                    save() {
                        const orderedIds = this.items.map(item => item.id);
                        this.$wire.saveSortOrder(orderedIds);
                    }
                }
            }
        </script>
    @endpush
@endonce