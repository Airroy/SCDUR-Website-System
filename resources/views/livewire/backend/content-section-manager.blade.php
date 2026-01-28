<div>
    <!-- Add Buttons -->
    <div class="flex space-x-2">
        @if($parentId === null)
            {{-- Root level: only show folder button --}}
            <button 
                wire:click="openAddFolderModal"
                class="inline-flex items-center px-3 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                </svg>
                เพิ่มหมวดหมู่
            </button>
        @else
            {{-- Sub levels: show buttons based on existing content --}}
            @if(!$hasFilesInParent)
                <button 
                    wire:click="openAddFolderModal"
                    class="inline-flex items-center px-3 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                    </svg>
                    เพิ่มหมวดหมู่
                </button>
            @endif
            @if(!$hasFoldersInParent)
            <button 
                wire:click="openAddFileModal"
                class="inline-flex items-center px-3 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                เพิ่มไฟล์
            </button>
            @endif
        @endif
    </div>

    <!-- Modal -->
    <x-backend.modal :show="$showModal">
        <x-backend.modal-form 
            wire:submit="saveNode" 
            :title="($editMode ? 'แก้ไข' : 'เพิ่ม') . ($type === 'folder' ? 'หมวดหมู่' : 'ไฟล์') . ($editMode ? '' : 'ใหม่')"
            :submitLabel="$editMode ? 'บันทึก' : 'เพิ่ม'"
        >
            <!-- Sequence -->
            <div class="mb-4">
                <label for="sequence" class="block text-sm font-medium text-gray-700">ลำดับ <span class="text-red-500">*</span></label>
                <input 
                    type="number" 
                    wire:model="sequence" 
                    id="sequence"
                    min="1"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                    placeholder="กรอกลำดับ (ห้ามซ้ำ)"
                >
                @error('sequence') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">
                    {{ $type === 'folder' ? 'ชื่อหมวดหมู่' : 'ชื่อไฟล์' }} <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    wire:model="name" 
                    id="name"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                    placeholder="{{ $type === 'folder' ? 'กรอกชื่อหมวดหมู่' : 'กรอกชื่อไฟล์' }}"
                >
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Image Upload (show only for root level folders) -->
            @if($type === 'folder' && $parentId === null)
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700">รูปภาพหมวดหมู่ <span class="text-red-500">*</span></label>
                
                @if($existingImage && !$image)
                    <div class="mt-2 mb-3">
                        <img src="{{ Storage::url($existingImage) }}" alt="Current image" class="h-32 w-auto rounded-lg shadow">
                        <p class="mt-1 text-xs text-gray-500">รูปภาพปัจจุบัน</p>
                    </div>
                @endif
                
                <input 
                    type="file" 
                    wire:model="image" 
                    id="image"
                    accept="image/*"
                    class="mt-2 block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-yellow-50 file:text-yellow-700
                        hover:file:bg-yellow-100"
                >
                <p class="mt-1 text-xs text-gray-500">รองรับไฟล์รูปภาพ (JPG, PNG, สูงสุด 2MB)</p>
                @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                
                @if($image)
                    <div class="mt-2">
                        <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="h-32 w-auto rounded-lg shadow">
                        <p class="mt-1 text-xs text-green-600">รูปภาพใหม่</p>
                    </div>
                @endif
                
                <div wire:loading wire:target="image" class="mt-2">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-yellow-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        กำลังอัปโหลด...
                    </div>
                </div>
            </div>
            @endif

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
                <p class="mt-1 text-xs text-gray-500">รองรับไฟล์ PDF เท่านั้น (สูงสุด 10MB)</p>
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