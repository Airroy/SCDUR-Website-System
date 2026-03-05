<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 - เกิดข้อผิดพลาด | {{ config('app.name', 'ARU-SCD') }}</title>
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
    <div class="bg-brand-yellow">
        <div class="max-w-7xl mx-auto px-4 py-2.5">
            <p class="text-center text-base font-light text-brand-blue">
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
                <div class="bg-gradient-to-r from-brand-red to-red-700 px-8 py-6 text-center">
                    <h1 class="text-7xl md:text-8xl font-bold text-white mb-2">500</h1>
                    <p class="text-red-200 text-sm font-medium tracking-wider">INTERNAL SERVER ERROR</p>
                </div>

                <!-- Body -->
                <div class="p-8 text-center">

                    <!-- Icon -->
                    <div class="mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-red-50 rounded-full">
                            <svg class="w-8 h-8 text-brand-red" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Message -->
                    <h2 class="text-xl font-bold text-gray-800 mb-3">เกิดข้อผิดพลาดภายในระบบ</h2>
                    <p class="text-gray-600 mb-6">
                        ขออภัยในความไม่สะดวก เกิดข้อผิดพลาดที่ไม่คาดคิด<br>
                        <span class="text-sm text-gray-500">กรุณาลองใหม่อีกครั้ง หรือติดต่อทีมงาน</span>
                    </p>

                    <!-- Error Details (only show in debug mode) -->
                    @if (config('app.debug') && isset($exception))
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6 text-left">
                            <h3 class="text-xs font-medium text-gray-800 mb-2">Debug Info:</h3>
                            <div class="text-xs text-gray-600 font-mono overflow-auto">
                                <p class="mb-1"><strong>Message:</strong> {{ $exception->getMessage() }}</p>
                                <p><strong>File:</strong> {{ $exception->getFile() }}:{{ $exception->getLine() }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <button onclick="location.reload()"
                            class="px-6 py-3 bg-brand-red text-white font-medium rounded-lg hover:bg-red-700 transition-colors duration-200">
                            ลองอีกครั้ง
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
    <footer class="bg-brand-dark text-center py-4 text-white text-sm">
        <p>&copy; {{ date('Y') }} {{ config('app.name', 'ARU-SCD') }} - Sustainable Community Development</p>
    </footer>
</body>

</html>
