<x-layouts.frontend>
    <x-slot:title>ติดต่อเรา</x-slot:title>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="bg-white relative w-full overflow-hidden shadow-lg border border-gray-200 p-6 sm:p- mb-6 sm:mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">ติดต่อเรา</h1>
                <!-- เส้นสีแดงสวยๆ -->
                <div class="mb-6">
                    <div class="h-1 bg-[#af1a00] shadow-sm"></div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- ข้อมูลติดต่อ -->
                <div class="bg-white relative w-full overflow-hidden shadow-lg p-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">ข้อมูลติดต่อ</h2>

                    <div class="space-y-4">
                        <div>
                            <h3 class="font-medium text-gray-900 mb-2">ที่อยู่</h3>
                            <p class="text-gray-600">
                                เลขที่ 96 ถ.ปรีดีพนมยงค์ ต.ประตูชัย<br>
                                อ.พระนครศรีอยุธยา จ.พระนครศรีอยุธยา 13000
                            </p>
                        </div>

                        <div>
                            <h3 class="font-medium text-gray-900 mb-2">อีเมล</h3>
                            <p class="text-gray-600">ISC@aru.ac.th</p>
                        </div>

                        <div>
                            <h3 class="font-medium text-gray-900 mb-2">โทรศัพท์</h3>
                            <p class="text-gray-600 text-lg font-semibold">0-3532-2589</p>
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
