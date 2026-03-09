<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - ไม่มีสิทธิ์เข้าถึง | {{ config('app.name', 'ARU-SCD') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 flex flex-col min-h-screen">
    <!-- Top Bar -->
    <div class="bg-brand-yellow">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 py-2 sm:py-2.5">
            <p class="text-center text-xs sm:text-sm md:text-base font-light text-brand-blue">
                Phranakhon Si Ayutthaya Rajabhat University
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-grow flex flex-col justify-center items-center px-4 py-8 sm:py-12">
        <div class="max-w-lg w-full">
            <!-- Error Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-brand-red to-red-700 px-4 sm:px-6 md:px-8 py-5 sm:py-6 text-center">
                    <h1 class="text-6xl sm:text-7xl md:text-8xl font-bold text-white mb-2">403</h1>
                    <p class="text-red-200 text-xs sm:text-sm font-medium tracking-wider">FORBIDDEN</p>
                </div>

                <!-- Body -->
                <div class="p-6 sm:p-8 text-center">
                    <!-- Icon -->
                    <div class="mb-5 sm:mb-6">
                        <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 bg-red-50 rounded-full">
                            <svg class="w-7 h-7 sm:w-8 sm:h-8 text-brand-red" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Message -->
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-2 sm:mb-3">ไม่มีสิทธิ์เข้าถึง</h2>
                    <p class="text-sm sm:text-base text-gray-600 mb-5 sm:mb-6 px-2">
                        ขออภัย คุณไม่มีสิทธิ์ในการเข้าถึงหน้านี้<br>
                        <span class="text-xs sm:text-sm text-gray-500 block mt-1">กรุณาตรวจสอบว่าคุณมีสิทธิ์ที่เหมาะสมหรือไม่</span>
                    </p>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 my-5 sm:my-6"></div>

                    <!-- Contact Info -->
                    <div class="bg-red-50 rounded-lg p-4 sm:p-5 mb-5 sm:mb-6">
                        <p class="text-sm sm:text-base text-gray-700 font-medium mb-2 sm:mb-3">หากต้องการขอสิทธิ์เข้าถึง</p>
                        <div class="space-y-1.5 sm:space-y-2 text-xs sm:text-sm text-gray-600">
                            <p>ติดต่อ: <span class="font-medium text-gray-800">สำนักงานวิทยบริการ</span></p>
                            <p>หรือ: <span class="font-medium text-gray-800">ห้อง IT / แผนก IT</span></p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-2.5 sm:gap-3 justify-center">
                        <a href="{{ auth()->check() ? route('admin.dashboard') : url('/') }}"
                            class="w-full sm:w-auto px-5 sm:px-6 py-2.5 sm:py-3 bg-brand-red text-white text-sm sm:text-base font-medium rounded-lg hover:bg-red-700 transition-colors duration-200">
                            กลับหน้าหลัก
                        </a>
                        <button onclick="history.back()"
                            class="w-full sm:w-auto px-5 sm:px-6 py-2.5 sm:py-3 bg-gray-100 text-gray-700 text-sm sm:text-base font-medium rounded-lg hover:bg-gray-200 transition-colors duration-200">
                            ย้อนกลับ
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-brand-dark text-center py-3 sm:py-4 text-white px-4">
        <p class="text-xs sm:text-sm leading-relaxed">
            &copy; {{ date('Y') }} {{ config('app.name', 'ARU-SCD') }}<br class="sm:hidden">
            <span class="hidden sm:inline"> - </span>Sustainable Community Development
        </p>
    </footer>
</body>
</html>