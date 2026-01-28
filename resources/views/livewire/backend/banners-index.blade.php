<div>
    @if($selectedYear)
        <!-- Tab Navigation -->
        <x-backend.year-tabs :selectedYear="$selectedYear" :currentPage="$currentPage" />

        <!-- Page Header -->
        <div class="p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">รูปภาพสไลด์ประจำปี {{ $selectedYear->year }}</h1>
                <p class="mt-1 text-gray-600">เพิ่ม แก้ไข และจัดการรูปภาพสไลด์บนหน้าหลัก</p>
            </div>

            @include('livewire.backend.partials.banners-table', ['banners' => $banners, 'selectedYear' => $selectedYear])
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">กรุณาเลือกปี</h3>
            <p class="mt-2 text-sm text-gray-500">กรุณาเลือกปีจากเมนูด้านซ้ายเพื่อเริ่มจัดการเนื้อหา</p>
        </div>
    @endif

    <!-- Add/Edit Banner Modal -->
    <x-backend.modal :show="$showModal" maxWidth="2xl">
        <form wire:submit.prevent="saveBanner">
            <div class="px-6 py-4 bg-white border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    {{ $editMode ? 'แก้ไข Banner' : 'เพิ่ม Banner' }}
                </h3>
            </div>

            <div class="px-6 py-4 space-y-4 max-h-[calc(100vh-200px)] overflow-y-auto">
                <!-- Sequence -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        ลำดับที่ <span class="text-red-600">*</span>
                    </label>
                    <input type="number" wire:model="sequence" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="เช่น 1, 2, 3..." min="1" required>
                    @error('sequence') <span class="text-sm text-red-600 mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Title (Optional) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        ชื่อแบนเนอร์ (ไม่บังคับ)
                    </label>
                    <input type="text" wire:model="title" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="ชื่อเพื่ออ้างอิง">
                    @error('title') <span class="text-sm text-red-600 mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Image Upload - Image Cropper -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        รูปภาพ Banner <span class="text-red-600">*</span>
                    </label>
                    
                    {{-- ใช้ Image Cropper Component --}}
                    <x-image-cropper-simple
                        name="banner_image"
                        :required="!$editMode"
                        :existingImage="$existingImage ? Storage::url($existingImage) : null"
                        aspectRatio="1920/720"
                        :outputWidth="1920"
                        :outputHeight="720"
                        helpText="รองรับ JPG, PNG ขนาดแนะนำ 1920x720 px"
                    />
                    
                    @error('banner_image') <span class="text-sm text-red-600 mt-1">{{ $message }}</span> @enderror
                    @error('image') <span class="text-sm text-red-600 mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Link Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        ประเภทลิงค์ <span class="text-red-600">*</span>
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" wire:model.live="link_type" value="none" class="mr-2">
                            <span class="text-sm">ไม่มีลิงค์</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" wire:model.live="link_type" value="url" class="mr-2">
                            <span class="text-sm">ใส่ลิงค์ URL</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" wire:model.live="link_type" value="pdf" class="mr-2">
                            <span class="text-sm">ใส่ไฟล์ PDF</span>
                        </label>
                    </div>
                </div>

                <!-- URL Input -->
                @if($link_type === 'url')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        URL <span class="text-red-600">*</span>
                    </label>
                    <input type="url" wire:model="link_url" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="https://example.com" required>
                    @error('link_url') <span class="text-sm text-red-600 mt-1">{{ $message }}</span> @enderror
                </div>
                @endif

                <!-- PDF Upload -->
                @if($link_type === 'pdf')
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            ไฟล์ PDF <span class="text-red-600">*</span>
                        </label>
                        <input type="file" wire:model="pdf_file" accept=".pdf"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        @error('pdf_file') <span class="text-sm text-red-600 mt-1">{{ $message }}</span> @enderror
                        
                        @if ($existingPdf && !$pdf_file)
                            <p class="text-sm text-gray-500 mt-1">
                                ไฟล์ปัจจุบัน: <a href="{{ Storage::url($existingPdf) }}" target="_blank" class="text-blue-600 hover:underline">ดูไฟล์</a>
                            </p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            ชื่อไฟล์ <span class="text-red-600">*</span>
                        </label>
                        <input type="text" wire:model="pdf_name" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                               placeholder="ชื่อไฟล์ที่จะแสดง" required>
                        @error('pdf_name') <span class="text-sm text-red-600 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                @endif
            </div>
            
            <div class="px-6 py-4 bg-gray-50 flex justify-end gap-2">
                <button type="button" wire:click="$set('showModal', false)"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    ยกเลิก
                </button>
                <button type="submit" 
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    {{ $editMode ? 'บันทึกการแก้ไข' : 'เพิ่ม Banner' }}
                </button>
            </div>
        </form>
    </x-backend.modal>
</div>