<div class="py-6 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="space-y-6">
    @if($report)
        <!-- Current Report Info -->
        <div class="p-4 sm:p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="flex-shrink-0 w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $report->file_name }}</h3>
                            <p class="text-sm text-gray-600">อัปเดตล่าสุด: {{ $report->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    
                    @if($report->file_path && Storage::disk('public')->exists($report->file_path))
                        <div class="flex items-center gap-4 mt-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                {{ number_format(Storage::disk('public')->size($report->file_path) / 1024, 2) }} KB
                            </span>
                            <a 
                                href="{{ Storage::url($report->file_path) }}" 
                                target="_blank"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-colors"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                เปิดดูไฟล์
                            </a>
                        </div>
                    @endif
                </div>
                
                <div class="flex-shrink-0 flex gap-2">
                    @if(!$isEditing)
                        <button 
                            wire:click="startEdit"
                            class="p-2 text-blue-600 hover:text-white hover:bg-blue-600 rounded-lg transition-colors focus:ring-4 focus:ring-blue-300"
                            title="แก้ไขรายงานผล"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                    @endif
                    
                    <button 
                        wire:click="deleteReport"
                        wire:confirm="คุณแน่ใจหรือไม่ว่าต้องการลบรายงานผลนี้?"
                        class="p-2 text-red-600 hover:text-white hover:bg-red-600 rounded-lg transition-colors focus:ring-4 focus:ring-red-300"
                        title="ลบรายงานผล"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Section (แสดงเฉพาะเมื่อยังไม่มีรายงาน หรือกำลังแก้ไข) -->
    @if(!$report || $isEditing)
    <div>
        <div class="mb-4">
            <h2 class="text-lg font-semibold text-gray-900">
                {{ $report ? 'แก้ไขข้อมูลรายงานผล' : 'เพิ่มรายงานผลใหม่' }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                {{ $report ? 'อัปเดตข้อมูลหรือเปลี่ยนไฟล์ PDF' : 'กรอกข้อมูลและอัปโหลดไฟล์ PDF รายงานผล' }}
            </p>
        </div>
        
        <form wire:submit.prevent="save" class="space-y-5">
            <!-- File Name -->
            <div>
                <label for="file_name" class="block mb-2 text-sm font-medium text-gray-900">
                    ชื่อไฟล์ <span class="text-red-600">*</span>
                </label>
                <input 
                    type="text" 
                    id="file_name"
                    wire:model="file_name"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5"
                    placeholder="เช่น รายงานผล SCD 2026"
                >
                @error('file_name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- PDF File Upload -->
            <div>
                <label for="pdf_file" class="block mb-2 text-sm font-medium text-gray-900">
                    ไฟล์ PDF {{ !$report ? '*' : '(เลือกไฟล์ใหม่หากต้องการเปลี่ยน)' }}
                </label>
                
                @if($existingFilePath && !$pdf_file)
                    <div class="mb-3 p-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-medium">ไฟล์ปัจจุบัน: มีไฟล์รายงานผลอยู่แล้ว</span>
                            </div>
                            @if(Storage::disk('public')->exists($existingFilePath))
                                <a href="{{ Storage::url($existingFilePath) }}" target="_blank" class="font-medium hover:underline">
                                    ดูไฟล์
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <input 
                    type="file" 
                    id="pdf_file"
                    wire:model="pdf_file"
                    accept=".pdf"
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100"
                >
                @error('pdf_file')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <p class="mt-1 text-sm text-gray-500">รองรับไฟล์ PDF ขนาดไม่เกิน 10 MB</p>

                <!-- Loading indicator -->
                <div wire:loading wire:target="pdf_file" class="mt-3">
                    <div class="flex items-center p-4 text-sm text-red-800 rounded-lg bg-red-50">
                        <svg class="animate-spin h-5 w-5 mr-3 text-red-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="font-medium">กำลังอัปโหลดไฟล์...</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-3 pt-2">
                <button 
                    type="submit"
                    class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled"
                    wire:target="save,pdf_file"
                >
                    <span wire:loading.remove wire:target="save">
                        <svg class="w-4 h-4 inline-block mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ $report ? 'บันทึกการแก้ไข' : 'บันทึกรายงานผล' }}
                    </span>
                    <span wire:loading wire:target="save">
                        <svg class="animate-spin h-4 w-4 inline-block mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        กำลังบันทึก...
                    </span>
                </button>
                
                @if($report)
                    <button 
                        type="button"
                        wire:click="cancelEdit"
                        class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5"
                    >
                        <svg class="w-4 h-4 inline-block mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        ยกเลิก
                    </button>
                @endif
            </div>
        </form>
    @endif
    </div>
    </div>
</div>
