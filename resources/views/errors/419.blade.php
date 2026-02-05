<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>419 - Session หมดอายุ | {{ config('app.name', 'ARU-SCD') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }
    </style>
</head>

<body class="antialiased bg-gray-50 flex flex-col min-h-screen">

    <!-- Top Bar -->
    <div class="bg-[#FFD87F]">
        <div class="max-w-7xl mx-auto px-4 py-2.5">
            <p class="text-center text-base font-light text-[#1e3c72]">
                Phranakhon Si Ayutthaya Rajabhat University
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-grow flex flex-col justify-center items-center px-4 py-12">
        <div class="max-w-lg w-full">

            <!-- Error Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">

                <!-- Header -->
                <div class="bg-gradient-to-r from-[#af1a00] to-red-700 px-8 py-6 text-center">
                    <h1 class="text-7xl md:text-8xl font-bold text-white mb-2">419</h1>
                    <p class="text-red-200 text-sm font-medium tracking-wider">SESSION EXPIRED</p>
                </div>

                <!-- Body -->
                <div class="p-8 text-center">

                    <!-- Icon -->
                    <div class="mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-red-50 rounded-full">
                            <svg class="w-8 h-8 text-[#af1a00]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Message -->
                    <h2 class="text-xl font-bold text-gray-800 mb-3">Session หมดอายุ</h2>
                    <p class="text-gray-600 mb-4">
                        การเข้าใช้งานของคุณหมดอายุแล้ว<br>
                        <span class="text-sm text-gray-500">กรุณารีเฟรชหน้าเว็บหรือเข้าสู่ระบบใหม่</span>
                    </p>

                    <!-- Security Info -->
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center justify-center mb-1">
                            <svg class="w-4 h-4 text-amber-600 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span class="text-sm font-medium text-amber-800">เพื่อความปลอดภัย</span>
                        </div>
                        <p class="text-xs text-amber-700">
                            ระบบจะล็อกเอาต์อัตโนมัติเมื่อไม่มีการใช้งานเป็นเวลานาน
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <button onclick="location.reload()"
                            class="px-6 py-3 bg-[#af1a00] text-white font-medium rounded-lg hover:bg-red-700 transition-colors duration-200">
                            รีเฟรชหน้า
                        </button>
                        <a href="{{ url('/') }}"
                            class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors duration-200">
                            กลับหน้าหลัก
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-[#2d2921] text-center py-4 text-white text-sm">
        <p>&copy; {{ date('Y') }} {{ config('app.name', 'ARU-SCD') }} - Sustainable Community Development</p>
    </footer>
</body>

</html>
