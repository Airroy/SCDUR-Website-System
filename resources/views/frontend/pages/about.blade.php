<x-layouts.frontend>
    <x-slot:title>เกี่ยวกับหน่วยงาน - มหาวิทยาลัยกับการพัฒนาชุมชนอย่างยั่งยืน</x-slot:title>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Box -->
            <div
                class="bg-white relative w-full overflow-hidden shadow-lg border border-gray-200 p-6 sm:p-8 mb-6 sm:mb-8">
                <!-- Breadcrumb -->
                <nav class="text-sm text-gray-600 mb-2">
                    <a href="{{ route('home') }}" class="hover:text-[#af1a00] hover:underline transition-all">
                        หน้าหลัก
                    </a>
                    <span class="mx-2 text-gray-400">›</span>
                    <span class="text-[#af1a00] font-semibold">เกี่ยวกับหน่วยงาน</span>
                </nav>

                <!-- เส้นสีแดงสวยๆ -->
                <div class="mb-6">
                    <div class="h-1 bg-[#af1a00] shadow-sm"></div>
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-6">เกี่ยวกับหน่วยงาน</h1>
            </div>

            <!-- Content Section -->
            <div class="bg-white rounded-lg shadow p-8">
                <p class="text-gray-600 text-center py-12">
                    ยังไม่มีข้อมูล
                </p>
            </div>
        </div>
    </div>
</x-layouts.frontend>
