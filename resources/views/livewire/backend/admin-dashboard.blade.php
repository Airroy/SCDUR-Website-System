<div>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-900">หน้าหลัก</h2>
        <p class="text-sm text-gray-500 mt-1">ภาพรวมระบบจัดการข้อมูล SCD</p>
    </x-slot>

    <div class="p-6 space-y-6">

        <!-- Welcome Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-red-600 via-red-500 to-red-700 p-8 text-white shadow-lg">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 right-24 w-40 h-40 bg-white/10 rounded-full translate-y-1/2"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-red-200 text-sm font-medium mb-1">ระบบจัดการข้อมูล</p>
                    <h3 class="text-2xl font-bold mb-2">ยินดีต้อนรับสู่ระบบจัดการ SCD</h3>
                    <p class="text-red-100 text-sm">เลือกปีจาก Sidebar ด้านซ้ายเพื่อเริ่มจัดการข้อมูล</p>
                </div>
                <a href="{{ url('/') }}" target="_blank"
                    class="flex-shrink-0 flex items-center gap-2 bg-white text-red-600 hover:bg-red-50 font-semibold text-sm px-5 py-2.5 rounded-xl shadow transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    ดูหน้าเว็บหลัก
                </a>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

            <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:border-red-200 hover:shadow-md transition-all duration-200">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">จำนวนปี</p>
                        <p class="text-4xl font-bold text-gray-900 mt-2 leading-none">{{ $stats['total_years'] }}</p>
                        <p class="text-xs text-gray-400 mt-2">ปีทั้งหมดในระบบ</p>
                    </div>
                    <div class="w-10 h-10 bg-red-50 group-hover:bg-red-100 rounded-xl flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 h-1 bg-red-100 rounded-full">
                    <div class="h-1 bg-red-400 rounded-full w-3/4"></div>
                </div>
            </div>

            <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:border-red-200 hover:shadow-md transition-all duration-200">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">เผยแพร่แล้ว</p>
                        <p class="text-4xl font-bold text-gray-900 mt-2 leading-none">{{ $stats['published_years'] }}</p>
                        <p class="text-xs text-gray-400 mt-2">ปีที่เผยแพร่แล้ว</p>
                    </div>
                    <div class="w-10 h-10 bg-red-50 group-hover:bg-red-100 rounded-xl flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 h-1 bg-red-100 rounded-full">
                    <div class="h-1 bg-red-400 rounded-full w-full"></div>
                </div>
            </div>

            <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:border-red-200 hover:shadow-md transition-all duration-200">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">รายงาน</p>
                        <p class="text-4xl font-bold text-gray-900 mt-2 leading-none">{{ $stats['total_reports'] }}</p>
                        <p class="text-xs text-gray-400 mt-2">รายงานทั้งหมด</p>
                    </div>
                    <div class="w-10 h-10 bg-red-50 group-hover:bg-red-100 rounded-xl flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 h-1 bg-red-100 rounded-full">
                    <div class="h-1 bg-red-400 rounded-full w-1/4"></div>
                </div>
            </div>

            <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:border-red-200 hover:shadow-md transition-all duration-200">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">แบนเนอร์</p>
                        <p class="text-4xl font-bold text-gray-900 mt-2 leading-none">{{ $stats['total_banners'] }}</p>
                        <p class="text-xs text-gray-400 mt-2">รูปภาพทั้งหมด</p>
                    </div>
                    <div class="w-10 h-10 bg-red-50 group-hover:bg-red-100 rounded-xl flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 h-1 bg-red-100 rounded-full">
                    <div class="h-1 bg-red-400 rounded-full w-2/3"></div>
                </div>
            </div>

        </div>
    </div>
</div>