<div>
    @if ($selectedYear)
        <!-- Tab Navigation -->
        <x-backend.year-tabs :selectedYear="$selectedYear" :currentPage="$currentPage" />
        <!-- Page Header -->
        <div class="p-6">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">ตัวชี้วัดประจำปี {{ $selectedYear->year }}</h1>
                <p class="mt-1 text-gray-600">เพิ่ม แก้ไข และจัดการตัวชี้วัดในแต่ละหัวข้อ</p>
            </div>
            <!-- Breadcrumbs Navigation -->
            @if (!empty($breadcrumbs))
                <nav class="mb-6 flex items-center space-x-2 text-sm bg-gray-50 px-4 py-3 rounded-lg">
                    <button wire:click="navigateBack(null)"
                        class="flex items-center text-gray-600 hover:text-red-600 hover:underline transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        หน้าแรก
                    </button>
                    @foreach ($breadcrumbs as $crumb)
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                        @if (!$loop->last)
                            <button wire:click="navigateBack({{ $crumb['parent_id'] }})"
                                class="text-gray-600 hover:text-red-600 hover:underline transition-colors">
                                {{ $crumb['name'] }}
                            </button>
                        @else
                            <span class="text-red-600 font-semibold">{{ $crumb['name'] }}</span>
                        @endif
                    @endforeach
                </nav>
            @endif
            <!-- Contents Table -->
            @include('livewire.backend.partials.contents-table', [
                'selectedYear' => $selectedYear,
                'contents' => $contents,
                'hasFolders' => $hasFolders,
                'hasFiles' => $hasFiles,
                'hasFoldersInParent' => $hasFoldersInParent,
                'hasFilesInParent' => $hasFilesInParent,
                'currentParentId' => $currentParentId,
            ])
        </div>
    @else
        <div class="flex items-center justify-center min-h-[60vh]">
            <div class="text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <h3 class="mt-4 text-xl font-semibold text-gray-900">กรุณาเลือกปี</h3>
                <p class="mt-2 text-gray-600">กรุณาเลือกปีจากเมนูด้านซ้ายเพื่อเริ่มจัดการเนื้อหา</p>
            </div>
        </div>
    @endif

    <!-- Sort Modal -->
    <x-backend.sort-modal :show="$showSortModal" :items="$sortableItems" title="จัดลำดับตัวชี้วัด" />

    <!-- Add/Edit Modal -->
    <x-backend.modal :show="$showModal">
        <form wire:submit.prevent="saveNode">
            <div class="px-6 py-4 bg-white border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    {{ ($editMode ? 'แก้ไข' : 'เพิ่ม') . ($type === 'folder' ? 'หมวดหมู่' : 'ไฟล์') . ($editMode ? '' : 'ใหม่') }}
                </h3>
            </div>

            <div class="px-6 py-4 space-y-4 max-h-[calc(100vh-200px)] overflow-y-auto">
                <!-- สถานะ (แสดงผล/ซ่อน) -->
                <div>
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
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        {{ $type === 'folder' ? 'ชื่อหมวดหมู่' : 'ชื่อไฟล์' }} <span class="text-red-600">*</span>
                    </label>
                    <input type="text" wire:model="name" id="name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                        placeholder="{{ $type === 'folder' ? 'กรอกชื่อหมวดหมู่' : 'กรอกชื่อไฟล์' }}">
                    @error('name')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Image Upload (root level folders only) -->
                @if ($type === 'folder' && $currentParentId === null)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            รูปภาพหมวดหมู่ <span class="text-red-600">*</span>
                        </label>

                        <x-image-cropper-simple name="image" label="" :required="!$editMode" :existingImage="$existingImage ? Storage::url($existingImage) : null"
                            aspectRatio="16/9" :outputWidth="1280" :outputHeight="720"
                            helpText="รองรับ JPG, PNG (สูงสุด 10MB)" />

                        @error('image')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                @endif

                <!-- File Upload (file type only) -->
                @if ($type === 'file')
                    <div>
                        <label for="file" class="block text-sm font-medium text-gray-700">
                            ไฟล์ PDF <span class="text-red-600">*</span>
                        </label>

                        @if ($existingFile && !$file)
                            <div class="mt-2 p-3 bg-gray-50 rounded-md flex items-center space-x-2">
                                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"></path>
                                </svg>
                                <span class="text-sm text-gray-600">ไฟล์ปัจจุบัน: {{ basename($existingFile) }}</span>
                            </div>
                        @endif

                        <input type="file" wire:model="file" id="file" accept=".pdf"
                            class="mt-2 block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-red-50 file:text-red-700
                            hover:file:bg-red-100">
                        <p class="mt-1 text-xs text-gray-500">รองรับไฟล์ PDF เท่านั้น (สูงสุด 100MB)</p>
                        @error('file')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror

                        {{-- ⏳ กำลังอัปโหลด --}}
                        <div wire:loading wire:target="file" class="mt-2">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-red-600" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                กำลังอัปโหลด...
                            </div>
                        </div>

                        {{-- ✅ อัปโหลดเสร็จแล้ว --}}
                        <div wire:loading.remove wire:target="file">
                            @if ($file)
                                <div class="mt-2 flex items-center text-sm text-green-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    อัปโหลดไฟล์เรียบร้อยแล้ว
                                </div>
                            @endif
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
                    {{ $editMode ? 'บันทึก' : 'เพิ่ม' }}
                </button>
            </div>
        </form>
    </x-backend.modal>
</div>
