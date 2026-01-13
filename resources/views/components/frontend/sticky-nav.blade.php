<!-- Sticky Navigation Bar -->
<nav class="bg-white shadow-sm sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-center items-center h-12 space-x-6">
            <a href="{{ route('home') }}" class="text-gray-700 hover:text-red-600 text-sm font-medium transition-colors">
                หน้าหลัก
            </a>
            <a href="{{ route('about') }}" class="text-gray-700 hover:text-red-600 text-sm font-medium transition-colors">
                เกี่ยวกับหน่วยงาน
            </a>
            <a href="{{ route('contact') }}" class="text-gray-700 hover:text-red-600 text-sm font-medium transition-colors">
                ติดต่อเรา
            </a>
        </div>
    </div>
</nav>
