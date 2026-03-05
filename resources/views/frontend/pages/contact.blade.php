<x-layouts.frontend>
    <x-slot:title>ติดต่อเรา - มหาวิทยาลัยกับการพัฒนาชุมชนอย่างยั่งยืน</x-slot:title>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="bg-white relative w-full overflow-hidden shadow-lg border border-gray-200 p-6 sm:p-8 mb-6 sm:mb-8">
                <!-- Breadcrumb -->
                <nav class="text-sm text-gray-600 mb-2">
                    <a href="{{ route('home') }}" class="hover:text-brand-red hover:underline transition-all">
                        หน้าหลัก
                    </a>
                    <span class="mx-2 text-gray-400">›</span>
                    <span class="text-brand-red font-semibold">ติดต่อเรา</span>
                </nav>

                <!-- เส้นสีแดงสวยๆ -->
                <div class="mb-6">
                    <div class="h-1 bg-brand-red shadow-sm"></div>
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-6">ติดต่อเรา</h1>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- ข้อมูลติดต่อ -->
                <div class="bg-white relative w-full overflow-hidden shadow-lg p-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">ข้อมูลติดต่อ</h2>
                    <div class="space-y-4">
                        <!-- ที่อยู่ -->
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">ที่อยู่</h3>
                                <p class="text-gray-600">
                                    มหาวิทยาลัยราชภัฏพระนครศรีอยุธยา <br>
                                    เลขที่ 96 ถ.ปรีดีพนมยงค์ ต.ประตูชัย <br>
                                    อ.พระนครศรีอยุธยา จ.พระนครศรีอยุธยา 13000
                                </p>
                            </div>
                        </div>

                        <!-- อีเมล -->
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">อีเมล</h3>
                                <p class="text-gray-600">ISC@aru.ac.th</p>
                            </div>
                        </div>

                        <!-- โทรศัพท์ -->
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">โทรศัพท์</h3>
                                <p class="text-gray-600">0-3532-2589</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Google Map -->
                <div class="bg-white shadow p-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">แผนที่</h2>

                    <div class="aspect-video overflow-hidden">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3871.4445!2d100.5667!3d14.3537!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTTCsDIxJzEzLjMiTiAxMDDCsDM0JzAwLjEiRQ!5e0!3m2!1sth!2sth!4v1234567890"
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade" class="w-full h-full"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.frontend>
