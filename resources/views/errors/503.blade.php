<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>503 - ระบบอยู่ในช่วงปรับปรุง | {{ config('app.name', 'ARU-SCD') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <style>body { font-family: 'Figtree', sans-serif; }</style>
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
                    <h1 class="text-7xl md:text-8xl font-bold text-white mb-2">503</h1>
                    <p class="text-red-200 text-sm font-medium tracking-wider">SERVICE UNAVAILABLE</p>
                </div>

                <!-- Body -->
                <div class="p-8 text-center">
                    
                    <!-- Icon -->
                    <div class="mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-amber-50 rounded-full">
                            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Message -->
                    <h2 class="text-xl font-bold text-gray-800 mb-3">ระบบอยู่ในช่วงปรับปรุง</h2>
                    <p class="text-gray-600 mb-4">
                        ขออภัยในความไม่สะดวก ขณะนี้ระบบอยู่ระหว่างการบำรุงรักษา<br>
                        <span class="text-sm text-gray-500">กรุณาลองใหม่อีกครั้งในอีกสักครู่</span>
                    </p>

                    <!-- Maintenance Info -->
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center justify-center mb-2">
                            <svg class="w-5 h-5 text-amber-600 animate-spin mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-sm font-medium text-amber-800">กำลังปรับปรุงระบบ</span>
                        </div>
                        <p class="text-xs text-amber-700">
                            หน้าจะรีเฟรชอัตโนมัติใน 30 วินาที
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

    <!-- Auto Refresh Script -->
    <script>
        setTimeout(function() { location.reload(); }, 30000);
    </script>
</body>
</html>