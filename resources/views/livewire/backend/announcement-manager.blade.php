<div>
    <!-- Add Buttons -->
    <div class="flex space-x-2">
        @if(!$hasFilesInParent)
            <x-backend.action-button color="yellow" label="เพิ่มหมวดหมู่" action="openAddFolderModal" />
        @endif
        @if(!$hasFoldersInParent)
            <x-backend.action-button color="red" label="เพิ่มไฟล์" action="openAddFileModal" />
        @endif
    </div>

    <!-- Modal -->
    <x-backend.modal :show="$showModal">
        <x-backend.modal-form 
            wire:submit="saveNode" 
            :title="($editMode ? 'แก้ไข' : 'เพิ่ม') . 
                    ($type === 'folder' ? 'หมวดหมู่' : 'ไฟล์') . 
                    ($categoryGroup === 'announcement' ? 'ประกาศ' : 'คำสั่ง') . 
                    ($editMode ? '' : 'ใหม่')"
            :submitLabel="$editMode ? 'บันทึก' : 'เพิ่ม'"
        >
            {{-- ✅ เอา block Sequence ออกแล้ว --}}

            <!-- สถานะ (แสดงผล/ซ่อน) -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    สถานะการแสดงผล
                </label>
                <div class="space-y-2">
                    <label
                        class="flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition-colors
                        {{ !$is_hidden ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-gray-300' }}">
                        <input type="radio" wire:model.live="is_hidden" value="0" class="hidden">
                        <div
                            class="w-4 h-4 rounded-full border-2 flex items-center justify-center flex-shrink-0
                            {{ !$is_hidden ? 'border-green-500' : 'border-gray-400' }}">
                            @if (!$is_hidden)
                                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-medium {{ !$is_hidden ? 'text-green-700' : 'text-gray-700' }}">
                                แสดงผล
                            </p>
                            <p class="text-xs {{ !$is_hidden ? 'text-green-500' : 'text-gray-400' }}">
                                รายการจะแสดงบนหน้าเว็บไซต์
                            </p>
                        </div>
                    </label>

                    <label
                        class="flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition-colors
                        {{ $is_hidden ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-gray-300' }}">
                        <input type="radio" wire:model.live="is_hidden" value="1" class="hidden">
                        <div
                            class="w-4 h-4 rounded-full border-2 flex items-center justify-center flex-shrink-0
                            {{ $is_hidden ? 'border-red-500' : 'border-gray-400' }}">
                            @if ($is_hidden)
                                <div class="w-2 h-2 rounded-full bg-red-500"></div>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-medium {{ $is_hidden ? 'text-red-700' : 'text-gray-700' }}">
                                ซ่อน (ไม่แสดงผล)
                            </p>
                            <p class="text-xs {{ $is_hidden ? 'text-red-500' : 'text-gray-400' }}">
                                รายการจะไม่แสดงบนหน้าเว็บไซต์
                            </p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">
                    {{ $type === 'folder' ? 'ชื่อหมวดหมู่' : 'ชื่อรายการ' }} <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    wire:model="name" 
                    id="name"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                    placeholder="{{ $type === 'folder' ? 'กรอกชื่อหมวดหมู่' : 'กรอกชื่อรายการ' }}"
                >
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- File Upload (show only if type is file) -->
            @if($type === 'file')
            <div class="mb-4">
                <label for="file" class="block text-sm font-medium text-gray-700">ไฟล์ PDF <span class="text-red-500">*</span></label>
                
                @if($existingFile && !$file)
                    <div class="mt-2 p-3 bg-gray-50 rounded-md flex items-center space-x-2">
                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"></path>
                        </svg>
                        <span class="text-sm text-gray-600">ไฟล์ปัจจุบัน: {{ basename($existingFile) }}</span>
                    </div>
                @endif
                
                <input 
                    type="file" 
                    wire:model="file" 
                    id="file"
                    accept=".pdf"
                    class="mt-2 block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-red-50 file:text-red-700
                        hover:file:bg-red-100"
                >
                <p class="mt-1 text-xs text-gray-500">รองรับไฟล์ PDF เท่านั้น (สูงสุด 100MB)</p>
                @error('file') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                
                <div wire:loading wire:target="file" class="mt-2">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        กำลังอัปโหลด...
                    </div>
                </div>
            </div>
            @endif
        </x-backend.modal-form>
    </x-backend.modal>
</div>