@props([
    'show' => false,
    'title' => '',
    'maxWidth' => 'lg',
    'closeEvent' => 'showModal',
    'uploading' => false,
    'uploadDone' => false,
])
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
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <!-- Modal panel -->
            <div
                class="relative bg-white rounded-lg text-left shadow-xl transform transition-all w-full overflow-hidden {{ $maxWidthClass }}">

                {{-- ✅ Upload Done Overlay --}}
                @if ($uploadDone)
                    <div
                        class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-white bg-opacity-90 rounded-lg">
                        <div class="flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-3">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <p class="text-green-700 font-semibold text-lg">อัพโหลดสำเร็จ!</p>
                        <p class="text-gray-500 text-sm mt-1">ไฟล์ของคุณถูกอัพโหลดเรียบร้อยแล้ว</p>
                    </div>
                @endif

                {{-- ⏳ Uploading Overlay --}}
                @if ($uploading && !$uploadDone)
                    <div
                        class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-white bg-opacity-80 rounded-lg">
                        <svg class="animate-spin w-10 h-10 text-blue-500 mb-3" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8h4z" />
                        </svg>
                        <p class="text-blue-600 font-medium text-base">กำลังอัพโหลด...</p>
                        <p class="text-gray-400 text-sm mt-1">กรุณารอสักครู่</p>
                    </div>
                @endif

                {{ $slot }}
            </div>
        </div>
    @endif
</div>
