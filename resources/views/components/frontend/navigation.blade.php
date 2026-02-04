@props(['publishedYears' => []])

<style>
/* Custom CSS for dropdown arrow rotation */
.dropdown-toggle i {
    transition: transform 0.2s ease;
}
.dropdown-toggle:hover i {
    transform: rotate(180deg);
}
</style>

<!-- Desktop Navigation -->
<nav class="hidden lg:block bg-[#af1a00] shadow-[0_2px_10px_rgba(0,0,0,0.2)] sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-5">
        <div class="flex items-center justify-between">
            <ul class="flex list-none m-0 p-0">
                
                <!-- หน้าหลัก -->
                <li class="relative">
                    <a href="{{ route('home') }}" 
                       class="block py-[18px] px-5 text-white no-underline text-[15px] font-normal transition-all duration-200 hover:bg-[#8a1500] whitespace-nowrap">
                        หน้าหลัก
                    </a>
                </li>

                <!-- เกี่ยวกับหน่วยงาน -->
                <li class="relative">
                    <a href="{{ route('about') }}" 
                       class="block py-[18px] px-5 text-white no-underline text-[15px] font-normal transition-all duration-200 hover:bg-[#8a1500] whitespace-nowrap">
                        เกี่ยวกับหน่วยงาน
                    </a>
                </li>

                <!-- SCD Rankings Dropdown -->
                <li class="relative group">
                    <a href="#" 
                       class="dropdown-toggle block py-[18px] px-5 text-white no-underline text-[15px] font-normal transition-all duration-200 hover:bg-[#8a1500] whitespace-nowrap">
                        SCD Rankings <i class="fa fa-chevron-down ml-1 text-[10px]"></i>
                    </a>
                    <!-- Dropdown -->
                    <ul class="absolute top-full left-0 bg-[#a82200] min-w-[250px] list-none p-0 m-0 shadow-[0_4px_15px_rgba(0,0,0,0.3)] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 rounded-b-lg">
                        @forelse($publishedYears as $year)
                        <li class="border-b border-white/5 last:border-0">
                            <a href="{{ route('home', ['year' => $year->id]) }}" 
                               class="block py-3 px-5 text-white no-underline text-sm transition-all duration-200 hover:bg-[#8a1500]">
                                SCD {{ $year->year }}
                            </a>
                        </li>
                        @empty
                        <li class="border-b border-white/5 last:border-0">
                            <span class="block py-3 px-5 text-white/60 text-sm">ไม่มีข้อมูล</span>
                        </li>
                        @endforelse
                    </ul>
                </li>

                <!-- รายงานผล SCD Dropdown -->
                <li class="relative group">
                    <a href="#" 
                       class="dropdown-toggle block py-[18px] px-5 text-white no-underline text-[15px] font-normal transition-all duration-200 hover:bg-[#8a1500] whitespace-nowrap">
                        รายงานผล SCD <i class="fa fa-chevron-down ml-1 text-[10px]"></i>
                    </a>
                    <!-- Dropdown -->
                    <ul class="absolute top-full left-0 bg-[#a82200] min-w-[250px] list-none p-0 m-0 shadow-[0_4px_15px_rgba(0,0,0,0.3)] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 rounded-b-lg">
                        @forelse($publishedYears as $year)
                            @if($year->report && $year->report->file_path)
                            <li class="border-b border-white/5 last:border-0">
                                <a href="{{ Storage::url($year->report->file_path) }}" 
                                   target="_blank"
                                   class="block py-3 px-5 text-white no-underline text-sm transition-all duration-200 hover:bg-[#8a1500]">
                                    รายงานผล SCD {{ $year->year }}
                                </a>
                            </li>
                            @endif
                        @empty
                        <li class="border-b border-white/5 last:border-0">
                            <span class="block py-3 px-5 text-white/60 text-sm">ไม่มีข้อมูล</span>
                        </li>
                        @endforelse
                    </ul>
                </li>

                <!-- ประกาศ/คำสั่ง Dropdown -->
                <li class="relative group">
                    <a href="#" 
                       class="dropdown-toggle block py-[18px] px-5 text-white no-underline text-[15px] font-normal transition-all duration-200 hover:bg-[#8a1500] whitespace-nowrap">
                        ประกาศ/คำสั่ง <i class="fa fa-chevron-down ml-1 text-[10px]"></i>
                    </a>
                    <!-- Dropdown -->
                    <ul class="absolute top-full left-0 bg-[#a82200] min-w-[250px] list-none p-0 m-0 shadow-[0_4px_15px_rgba(0,0,0,0.3)] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 rounded-b-lg">
                        @forelse($publishedYears as $year)
                        <li class="border-b border-white/5 last:border-0">
                            <a href="{{ route('announcements', $year->id) }}" 
                               class="block py-3 px-5 text-white no-underline text-sm transition-all duration-200 hover:bg-[#8a1500]">
                                ประกาศ/คำสั่ง {{ $year->year }}
                            </a>
                        </li>
                        @empty
                        <li class="border-b border-white/5 last:border-0">
                            <span class="block py-3 px-5 text-white/60 text-sm">ไม่มีข้อมูล</span>
                        </li>
                        @endforelse
                    </ul>
                </li>

                <!-- ติดต่อเรา -->
                <li class="relative">
                    <a href="{{ route('contact') }}" 
                       class="block py-[18px] px-5 text-white no-underline text-[15px] font-normal transition-all duration-200 hover:bg-[#8a1500] whitespace-nowrap">
                        ติดต่อเรา
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Mobile Navigation -->
<header class="lg:hidden sticky w-full z-40 top-0" x-data="{ mobileMenuOpen: false, activeDropdown: null }">
    <!-- Top Bar -->
    <nav class="bg-[#af1a00] shadow-lg">
        <div class="flex flex-wrap justify-between items-center mx-auto px-4 py-2.5">
            <a href="{{ route('home') }}" class="flex items-center">
                <span class="text-base text-white font-semibold">ARU-SCD</span>
            </a>
            <button @click="mobileMenuOpen = !mobileMenuOpen" 
                    class="text-white text-xl p-1.5 hover:bg-[#8a1500] rounded-lg transition-all duration-200">
                <span x-show="!mobileMenuOpen">☰</span>
                <span x-show="mobileMenuOpen">☰</span>
            </button>
        </div>
    </nav>

    <!-- Slide Down Menu -->
    <nav x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-4"
         @click.away="mobileMenuOpen = false"
         class="bg-[#a82200] shadow-xl max-h-[calc(100vh-52px)] overflow-y-auto">
        <div class="px-3 py-3">
            <ul class="space-y-1">
                <!-- หน้าหลัก -->
                <li>
                    <a href="{{ route('home') }}" 
                       class="block py-2.5 px-4 text-white text-sm font-medium transition-all duration-200 hover:bg-[#8a1500] rounded-lg">
                        หน้าหลัก
                    </a>
                </li>

                <!-- เกี่ยวกับหน่วยงาน -->
                <li>
                    <a href="{{ route('about') }}" 
                       class="block py-2.5 px-4 text-white text-sm font-medium transition-all duration-200 hover:bg-[#8a1500] rounded-lg">
                        เกี่ยวกับหน่วยงาน
                    </a>
                </li>

                <!-- SCD Rankings -->
                <li>
                    <button @click="activeDropdown = activeDropdown === 'rankings' ? null : 'rankings'" 
                            type="button"
                            class="w-full flex justify-between items-center py-2.5 px-4 text-white text-sm font-medium text-left transition-all duration-200 hover:bg-[#8a1500] rounded-lg">
                        <span>SCD Rankings</span>
                        <i class="fa fa-chevron-down text-[10px] transition-transform duration-200" :class="{ 'rotate-180': activeDropdown === 'rankings' }"></i>
                    </button>
                    <ul x-show="activeDropdown === 'rankings'" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 max-h-0"
                        x-transition:enter-end="opacity-100 max-h-96"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 max-h-96"
                        x-transition:leave-end="opacity-0 max-h-0"
                        class="bg-[#8a1500] rounded-lg mt-1 ml-3 overflow-hidden">
                        @forelse($publishedYears as $year)
                        <li>
                            <a href="{{ route('home', ['year' => $year->id]) }}" 
                               class="block py-2 px-4 text-white text-sm transition-all duration-200 hover:bg-[#6b1000]">
                                SCD {{ $year->year }}
                            </a>
                        </li>
                        @empty
                        <li>
                            <span class="block py-2 px-4 text-white/60 text-sm">ไม่มีข้อมูล</span>
                        </li>
                        @endforelse
                    </ul>
                </li>

                <!-- รายงานผล SCD -->
                <li>
                    <button @click="activeDropdown = activeDropdown === 'reports' ? null : 'reports'" 
                            type="button"
                            class="w-full flex justify-between items-center py-2.5 px-4 text-white text-sm font-medium text-left transition-all duration-200 hover:bg-[#8a1500] rounded-lg">
                        <span>รายงานผล SCD</span>
                        <i class="fa fa-chevron-down text-[10px] transition-transform duration-200" :class="{ 'rotate-180': activeDropdown === 'reports' }"></i>
                    </button>
                    <ul x-show="activeDropdown === 'reports'" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 max-h-0"
                        x-transition:enter-end="opacity-100 max-h-96"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 max-h-96"
                        x-transition:leave-end="opacity-0 max-h-0"
                        class="bg-[#8a1500] rounded-lg mt-1 ml-3 overflow-hidden">
                        @forelse($publishedYears as $year)
                            @if($year->report && $year->report->file_path)
                            <li>
                                <a href="{{ Storage::url($year->report->file_path) }}" 
                                   target="_blank"
                                   class="block py-2 px-4 text-white text-sm transition-all duration-200 hover:bg-[#6b1000]">
                                    รายงาน SCD {{ $year->year }}
                                </a>
                            </li>
                            @endif
                        @empty
                        <li>
                            <span class="block py-2 px-4 text-white/60 text-sm">ไม่มีข้อมูล</span>
                        </li>
                        @endforelse
                    </ul>
                </li>

                <!-- ประกาศ/คำสั่ง -->
                <li>
                    <button @click="activeDropdown = activeDropdown === 'announcements' ? null : 'announcements'" 
                            type="button"
                            class="w-full flex justify-between items-center py-2.5 px-4 text-white text-sm font-medium text-left transition-all duration-200 hover:bg-[#8a1500] rounded-lg">
                        <span>ประกาศ/คำสั่ง</span>
                        <i class="fa fa-chevron-down text-[10px] transition-transform duration-200" :class="{ 'rotate-180': activeDropdown === 'announcements' }"></i>
                    </button>
                    <ul x-show="activeDropdown === 'announcements'" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 max-h-0"
                        x-transition:enter-end="opacity-100 max-h-96"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 max-h-96"
                        x-transition:leave-end="opacity-0 max-h-0"
                        class="bg-[#8a1500] rounded-lg mt-1 ml-3 overflow-hidden">
                        @forelse($publishedYears as $year)
                        <li>
                            <a href="{{ route('announcements', $year->id) }}" 
                               class="block py-2 px-4 text-white text-sm transition-all duration-200 hover:bg-[#6b1000]">
                                ประกาศ/คำสั่ง {{ $year->year }}
                            </a>
                        </li>
                        @empty
                        <li>
                            <span class="block py-2 px-4 text-white/60 text-sm">ไม่มีข้อมูล</span>
                        </li>
                        @endforelse
                    </ul>
                </li>

                <!-- ติดต่อเรา -->
                <li>
                    <a href="{{ route('contact') }}" 
                       class="block py-2.5 px-4 text-white text-sm font-medium transition-all duration-200 hover:bg-[#8a1500] rounded-lg">
                        ติดต่อเรา
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>