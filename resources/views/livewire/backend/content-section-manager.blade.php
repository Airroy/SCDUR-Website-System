<div class="py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Breadcrumbs -->
    @if(count($breadcrumbs) > 0 || $currentNode)
        <div class="mb-4 flex flex-wrap items-center gap-2 text-sm sm:text-base">
            <button 
                wire:click="navigateTo(null)"
                class="text-red-600 hover:text-red-800 hover:underline font-medium"
            >
                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="ml-1">หน้าหลัก</span>
            </button>
            @foreach($breadcrumbs as $crumb)
                <span class="text-gray-400">/</span>
                <button 
                    wire:click="navigateTo({{ $crumb->id }})"
                    class="text-red-600 hover:text-red-800 hover:underline font-medium"
                >
                    {{ $crumb->name }}
                </button>
            @endforeach
        </div>
    @endif

    <!-- Grid of Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @php
            // Check if we're at root level
            $isRootLevel = !$currentNode;
            
            // Check if there are any folders or files at current level
            $hasFolders = $nodes->where('type', 'folder')->count() > 0;
            $hasFiles = $nodes->where('type', 'file')->count() > 0;
        @endphp

        <!-- Add Folder Card -->
        <!-- At root level: always show -->
        <!-- At sub-levels: show only if no files exist -->
        @if($isRootLevel || !$hasFiles)
            <x-admin::card-add 
                wire:click="openFolderModal"
                title="เพิ่มหมวดหมู่หัวข้อ"
                subtitle="คลิกเพื่อสร้างหมวดหมู่ใหม่"
                color="red"
            />
        @endif

        <!-- Add File Card - Show only at sub-levels and only if no folders exist -->
        @if(!$isRootLevel && !$hasFolders)
            <x-admin::card-add 
                wire:click="openFileModal"
                title="เพิ่มไฟล์"
                subtitle="คลิกเพื่ออัปโหลดไฟล์ PDF"
                color="blue"
            />
        @endif

        <!-- Existing Nodes -->
        @foreach($nodes as $node)
            <x-admin::card-item 
                :title="$node->name"
                x-data="{ open: false }"
            >
                <x-slot:icon>
                    @if($node->isFolder())
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0 transition-colors group-hover:bg-red-200">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                            </svg>
                        </div>
                    @else
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0 transition-colors group-hover:bg-red-200">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                </x-slot:icon>

                <x-slot:actions>
                    <!-- Three-dot Menu -->
                    <div class="relative flex-shrink-0">
                        <button 
                            @click="open = !open"
                            @click.outside="open = false"
                            class="p-2 text-gray-600 hover:bg-gray-100 rounded-full transition-colors"
                        >
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="5" r="2"></circle>
                                <circle cx="12" cy="12" r="2"></circle>
                                <circle cx="12" cy="19" r="2"></circle>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div 
                            x-show="open"
                            x-transition
                            x-cloak
                            class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-10"
                            style="display: none;"
                        >
                            @if($node->isFolder())
                                <button 
                                    wire:click="navigateTo({{ $node->id }})"
                                    @click="open = false"
                                    class="w-full flex items-center px-4 py-2.5 text-base text-green-700 hover:bg-green-50 transition-colors"
                                >
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    เปิดหมวดหมู่
                                </button>
                            @else
                                @if($node->file_path && Storage::disk('public')->exists($node->file_path))
                                    <a 
                                        href="{{ Storage::url($node->file_path) }}" 
                                        target="_blank"
                                        class="flex items-center px-4 py-2.5 text-base text-blue-700 hover:bg-blue-50 transition-colors"
                                    >
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        ดูไฟล์ PDF
                                    </a>
                                @endif
                            @endif
                            
                            <button 
                                wire:click="openEditModal({{ $node->id }})"
                                @click="open = false"
                                class="w-full flex items-center px-4 py-2.5 text-base text-yellow-700 hover:bg-yellow-50 transition-colors"
                            >
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                แก้ไข
                            </button>
                            
                            <button 
                                wire:click="deleteNode({{ $node->id }})"
                                wire:confirm="คุณแน่ใจหรือไม่? {{ $node->hasChildren() ? 'การลบหมวดหมู่จะลบข้อมูลภายในทั้งหมดด้วย' : '' }}"
                                @click="open = false"
                                class="w-full flex items-center px-4 py-2.5 text-base text-red-700 hover:bg-red-50 transition-colors"
                            >
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                ลบ
                            </button>
                        </div>
                    </div>
                </x-slot:actions>
            </x-admin::card-item>
        @endforeach
    </div>

    <!-- Modal for Add/Edit -->
    @if($showModal)
        <x-admin::modal 
            :title="($editMode ? 'แก้ไข' : 'เพิ่ม') . ($modalType === 'folder' ? 'หมวดหมู่หัวข้อ' : 'ไฟล์') . ($editMode ? '' : 'ใหม่')" 
            maxWidth="2xl"
        >
            <form wire:submit.prevent="save" class="p-6 space-y-4">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                ชื่อ{{ $modalType === 'folder' ? 'หัวข้อ' : 'ไฟล์' }} <span class="text-red-600">*</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model="name"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                placeholder="กรอกชื่อ{{ $modalType === 'folder' ? 'หัวข้อ' : 'ไฟล์' }}"
                            >
                            @error('name') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Sequence -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                ลำดับ <span class="text-red-600">*</span>
                            </label>
                            <input 
                                type="number" 
                                wire:model="sequence"
                                min="1"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                placeholder="กรอกลำดับ (ห้ามซ้ำ)"
                            >
                            @error('sequence') <span class="text-red-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Image Upload (only for root level folders) -->
                        @if($modalType === 'folder' && !$currentNode)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    รูปภาพหัวข้อ <span class="text-red-600">*</span>
                                </label>
                                
                                @if($existingImagePath && !$image)
                                    <div class="mb-3">
                                        <img 
                                            src="{{ Storage::url($existingImagePath) }}" 
                                            alt="Current image"
                                            class="w-32 h-32 object-cover rounded-lg border-2 border-gray-300"
                                        >
                                        <p class="text-xs text-gray-500 mt-1">รูปภาพปัจจุบัน</p>
                                    </div>
                                @endif

                                <input 
                                    type="file" 
                                    wire:model="image"
                                    accept="image/*"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                >
                                @error('image') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                                
                                @if($image)
                                    <div class="mt-3 flex items-center gap-2 p-3 bg-green-50 rounded-lg border border-green-200">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm text-green-700">เลือกรูปภาพใหม่แล้ว</span>
                                    </div>
                                @endif
                                
                                <p class="text-xs text-gray-500 mt-1">รองรับไฟล์ JPG, PNG (ขนาดไม่เกิน 5MB)</p>
                            </div>
                        @endif

                        <!-- PDF Upload (for file type) -->
                        @if($modalType === 'file')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    ไฟล์ PDF <span class="text-red-600">*</span>
                                </label>
                                
                                @if($existingFilePath && !$pdf_file)
                                    <div class="mb-3 flex items-center gap-2 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-sm text-blue-700">มีไฟล์ PDF อยู่แล้ว</span>
                                    </div>
                                @endif

                                <input 
                                    type="file" 
                                    wire:model="pdf_file"
                                    accept=".pdf"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                >
                                @error('pdf_file') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                                <p class="text-xs text-gray-500 mt-1">รองรับไฟล์ PDF (ขนาดไม่เกิน 10MB)</p>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex justify-end gap-3 pt-4">
                            <button 
                                type="button"
                                wire:click="closeModal"
                                class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors"
                            >
                                ยกเลิก
                            </button>
                            <button 
                                type="submit"
                                class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors"
                            >
                                {{ $editMode ? 'บันทึก' : 'เพิ่ม' }}
                            </button>
                        </div>
                    </form>
        </x-admin::modal>
    @endif
    </div>
</div>
