<div>
    @if ($selectedYear)
        <!-- Tab Navigation -->
        <x-backend.year-tabs :selectedYear="$selectedYear" :currentPage="$currentPage" />

        <!-- Page Header -->
        <div class="p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">รายงานผลประจำปี {{ $selectedYear->year }}</h1>
                <p class="mt-1 text-gray-600">เพิ่ม แก้ไข และจัดการไฟล์รายงาน SCD</p>
            </div>

            <!-- Reports Table -->
            <div class="bg-white rounded-lg shadow">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">รายงานผล SCD</h3>
                    @if ($reports->isEmpty())
                        <button wire:click="openAddModal"
                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            เพิ่มรายงาน
                        </button>
                    @endif
                </div>

                <!-- Table or Empty State -->
                @if ($reports->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">ยังไม่มีรายงานผล</h3>
                        <p class="mt-1 text-sm text-gray-500">เริ่มต้นโดยการเพิ่มรายงานผล SCD</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ชื่อรายงาน</th>
                                    <th scope="col"
                                        class="hidden md:table-cell w-40 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ไฟล์ PDF</th>
                                    <th scope="col"
                                        class="hidden lg:table-cell w-48 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        อัปเดต</th>
                                    <th scope="col"
                                        class="w-56 px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        จัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($reports as $report)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 sm:px-6 py-4">
                                            <div class="max-w-md overflow-hidden">
                                                <div class="text-sm font-medium text-gray-900 truncate w-full"
                                                    title="{{ $report->file_name }}">{{ $report->file_name }}</div>
                                            </div>
                                        </td>
                                        <td class="hidden md:table-cell px-6 py-4">
                                            @if ($report->file_path && Storage::disk('public')->exists($report->file_path))
                                                <a href="{{ Storage::url($report->file_path) }}" target="_blank"
                                                    class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                    ดูไฟล์
                                                </a>
                                            @else
                                                <span class="text-sm text-gray-400">ไม่มีไฟล์</span>
                                            @endif
                                        </td>
                                        <td
                                            class="hidden lg:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $report->updated_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                            <div class="flex flex-wrap items-center gap-1 sm:gap-2">
                                                <button wire:click="openEditModal({{ $report->id }})"
                                                    class="px-2 sm:px-3 py-1 sm:py-1.5 text-xs sm:text-sm border border-yellow-600 text-yellow-600 rounded hover:bg-yellow-600 hover:text-white transition-colors duration-200">
                                                    แก้ไข
                                                </button>
                                                <button wire:click="deleteReport({{ $report->id }})"
                                                    wire:confirm="คุณแน่ใจหรือไม่ที่จะลบรายงานนี้?"
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
                @endif
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">กรุณาเลือกปี</h3>
            <p class="mt-2 text-sm text-gray-500">กรุณาเลือกปีจากเมนูด้านซ้ายเพื่อเริ่มจัดการเนื้อหา</p>
        </div>
    @endif

    <!-- Add/Edit Report Modal -->
    <x-backend.modal :show="$showModal" maxWidth="2xl">
        <form wire:submit.prevent="saveReport">
            <div class="px-6 py-4 bg-white border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    {{ $editMode ? 'แก้ไขรายงาน' : 'เพิ่มรายงาน' }}
                </h3>
            </div>

            <div class="px-6 py-4 space-y-4">
                <!-- PDF File Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        ไฟล์ PDF {{ !$editMode ? '*' : '' }}
                    </label>

                    @if ($editMode && $existingFile)
                        <div class="mb-3 p-3 bg-gray-50 rounded-lg flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span class="text-sm text-gray-700">ไฟล์ปัจจุบัน</span>
                            </div>
                            <a href="{{ Storage::url($existingFile) }}" target="_blank"
                                class="text-sm text-blue-600 hover:text-blue-800">
                                ดูไฟล์
                            </a>
                        </div>
                    @endif

                    <div class="flex items-center justify-center w-full">
                        <label
                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-2 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500">
                                    <span class="font-semibold">คลิกเพื่อเลือกไฟล์</span> หรือลากไฟล์มาวาง
                                </p>
                                <p class="text-xs text-gray-500">PDF (สูงสุด 100MB)</p>
                            </div>
                            <input type="file" wire:model="file" accept=".pdf" class="hidden">
                        </label>
                    </div>

                    @if ($file)
                        <div class="mt-2 text-sm text-green-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ $file->getClientOriginalName() }}
                        </div>
                    @endif

                    @error('file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div wire:loading wire:target="file" class="mt-2 text-sm text-blue-600">
                        กำลังอัปโหลดไฟล์...
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                <button type="button" wire:click="$set('showModal', false)"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                    ยกเลิก
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="saveReport">บันทึก</span>
                    <span wire:loading wire:target="saveReport">กำลังบันทึก...</span>
                </button>
            </div>
        </form>
    </x-backend.modal>
</div>
