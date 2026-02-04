<x-layouts.frontend :title="'ประกาศ/คำสั่ง - SCD ' . $year->year">
    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Box สำหรับ Breadcrumb และ Title -->
            <div class="bg-white relative w-full overflow-hidden rounded-xl shadow-lg border border-gray-200 p-6 sm:p-8 mb-6 sm:mb-8">
                <!-- Breadcrumb -->
                <nav class="text-sm text-gray-600 mb-2">
                    <a href="{{ route('home') }}" class="hover:text-[#af1a00] transition-colors">หน้าหลัก</a>
                    <span class="mx-2 text-gray-400">›</span>
                    <span class="text-[#af1a00] font-semibold">ประกาศ/คำสั่ง SCD {{ $year->year }}</span>
                </nav>

                <!-- เส้นสีแดงสวยๆ -->
                <div class="mb-6">
                    <div class="h-1 bg-[#af1a00] rounded-full shadow-sm"></div>
                </div>

                <!-- Title -->
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                    ประกาศ/คำสั่ง SCD {{ $year->year }}
                </h1>
            </div>
            
            <!-- ประกาศ -->
            <div class="mb-8 sm:mb-12">
                <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                    <div class="bg-[#af1a00] px-4 sm:px-6 py-3 sm:py-4">
                        <h2 class="text-lg sm:text-xl font-semibold text-white">
                            ประกาศ
                        </h2>
                    </div>
                    
                    @if($announcements->count() > 0)
                        <div>
                            <x-frontend.content-tree :items="$announcements" />
                        </div>
                    @else
                        <div class="text-center py-12 sm:py-16 px-4">
                            <svg class="mx-auto h-12 w-12 sm:h-16 sm:w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-3 sm:mt-4 text-base sm:text-lg text-gray-500">ยังไม่มีข้อมูลประกาศ</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- คำสั่ง -->
            <div>
                <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                    <div class="bg-[#af1a00] px-4 sm:px-6 py-3 sm:py-4">
                        <h2 class="text-lg sm:text-xl font-semibold text-white">
                            คำสั่ง
                        </h2>
                    </div>
                    
                    @if($orders->count() > 0)
                        <div>
                            <x-frontend.content-tree :items="$orders" />
                        </div>
                    @else
                        <div class="text-center py-12 sm:py-16 px-4">
                            <svg class="mx-auto h-12 w-12 sm:h-16 sm:w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-3 sm:mt-4 text-base sm:text-lg text-gray-500">ยังไม่มีข้อมูลคำสั่ง</p>
                        </div>
                    @endif
                </div>
            </div>
            
        </div>
    </div>
</x-layouts.frontend>