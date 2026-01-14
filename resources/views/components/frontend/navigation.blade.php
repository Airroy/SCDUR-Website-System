@props(['publishedYears' => []])

<nav class="bg-[#af1a00] shadow-[0_2px_10px_rgba(0,0,0,0.2)] sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-5">
        <div class="flex items-center justify-between">
            
            <!-- Mobile Menu Toggle -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" 
                    class="lg:hidden text-white text-2xl p-2.5">
                ☰
            </button>

            <!-- Desktop Menu -->
            <ul class="hidden lg:flex list-none m-0 p-0">
                
                <!-- หน้าหลัก -->
                <li class="relative">
                    <a href="{{ route('home') }}" 
                       class="block py-[18px] px-5 text-white no-underline text-[15px] font-normal transition-all duration-300 hover:bg-[#ff9f8e] hover:text-black whitespace-nowrap">
                        หน้าหลัก
                    </a>
                </li>

                <!-- เกี่ยวกับหน่วยงาน -->
                <li class="relative">
                    <a href="{{ route('about') }}" 
                       class="block py-[18px] px-5 text-white no-underline text-[15px] font-normal transition-all duration-300 hover:bg-[#ff9f8e] hover:text-black whitespace-nowrap">
                        เกี่ยวกับหน่วยงาน
                    </a>
                </li>

                <!-- SCD Rankings Dropdown -->
                <li class="relative group">
                    <a href="#" 
                       class="block py-[18px] px-5 text-white no-underline text-[15px] font-normal transition-all duration-300 hover:bg-[#ff9f8e] hover:text-black whitespace-nowrap">
                        SCD Rankings <span class="text-xs">▾</span>
                    </a>
                    <!-- Dropdown -->
                    <ul class="absolute top-full left-0 bg-white min-w-[250px] list-none p-0 m-0 shadow-[0_4px_15px_rgba(0,0,0,0.2)] opacity-0 invisible translate-y-[-10px] group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-300 rounded-b-lg">
                        @forelse($publishedYears as $year)
                        <li class="border-b border-gray-200 last:border-0">
                            <a href="{{ route('home', ['year' => $year->id]) }}" 
                               class="block py-3 px-5 text-gray-600 no-underline text-sm transition-all duration-300 hover:bg-gray-100 hover:text-black hover:pl-[25px]">
                                SCD {{ $year->year }}
                            </a>
                        </li>
                        @empty
                        <li class="border-b border-gray-200 last:border-0">
                            <span class="block py-3 px-5 text-gray-400 text-sm">ไม่มีข้อมูล</span>
                        </li>
                        @endforelse
                    </ul>
                </li>

                <!-- รายงานผล SCD Dropdown -->
                <li class="relative group">
                    <a href="#" 
                       class="block py-[18px] px-5 text-white no-underline text-[15px] font-normal transition-all duration-300 hover:bg-[#ff9f8e] hover:text-black whitespace-nowrap">
                        รายงานผล SCD <span class="text-xs">▾</span>
                    </a>
                    <!-- Dropdown -->
                    <ul class="absolute top-full left-0 bg-white min-w-[250px] list-none p-0 m-0 shadow-[0_4px_15px_rgba(0,0,0,0.2)] opacity-0 invisible translate-y-[-10px] group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-300 rounded-b-lg">
                        @forelse($publishedYears as $year)
                            @if($year->report && $year->report->file_path)
                            <li class="border-b border-gray-200 last:border-0">
                                <a href="{{ Storage::url($year->report->file_path) }}" 
                                   target="_blank"
                                   class="block py-3 px-5 text-gray-600 no-underline text-sm transition-all duration-300 hover:bg-gray-100 hover:text-black hover:pl-[25px]">
                                    รายงาน SCD {{ $year->year }}
                                </a>
                            </li>
                            @endif
                        @empty
                        <li class="border-b border-gray-200 last:border-0">
                            <span class="block py-3 px-5 text-gray-400 text-sm">ไม่มีข้อมูล</span>
                        </li>
                        @endforelse
                    </ul>
                </li>

                <!-- ประกาศ/คำสั่ง Dropdown -->
                <li class="relative group">
                    <a href="#" 
                       class="block py-[18px] px-5 text-white no-underline text-[15px] font-normal transition-all duration-300 hover:bg-[#ff9f8e] hover:text-black whitespace-nowrap">
                        ประกาศ/คำสั่ง <span class="text-xs">▾</span>
                    </a>
                    <!-- Dropdown -->
                    <ul class="absolute top-full left-0 bg-white min-w-[250px] list-none p-0 m-0 shadow-[0_4px_15px_rgba(0,0,0,0.2)] opacity-0 invisible translate-y-[-10px] group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 transition-all duration-300 rounded-b-lg">
                        @forelse($publishedYears as $year)
                        <li class="border-b border-gray-200 last:border-0">
                            <a href="{{ route('announcements', $year->id) }}" 
                               class="block py-3 px-5 text-gray-600 no-underline text-sm transition-all duration-300 hover:bg-gray-100 hover:text-black hover:pl-[25px]">
                                ประกาศ/คำสั่ง {{ $year->year }}
                            </a>
                        </li>
                        @empty
                        <li class="border-b border-gray-200 last:border-0">
                            <span class="block py-3 px-5 text-gray-400 text-sm">ไม่มีข้อมูล</span>
                        </li>
                        @endforelse
                    </ul>
                </li>

                <!-- ติดต่อเรา -->
                <li class="relative">
                    <a href="{{ route('contact') }}" 
                       class="block py-[18px] px-5 text-white no-underline text-[15px] font-normal transition-all duration-300 hover:bg-[#ff9f8e] hover:text-black whitespace-nowrap">
                        ติดต่อเรา
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" 
         x-transition
         @click.away="mobileMenuOpen = false"
         class="lg:hidden fixed left-0 top-0 w-4/5 max-w-[300px] h-screen bg-[#af1a00] pt-[60px] pb-5 overflow-y-auto z-50">
        
        <ul class="list-none m-0 p-0">
            <!-- หน้าหลัก -->
            <li class="w-full">
                <a href="{{ route('home') }}" 
                   class="block py-[15px] px-5 text-white no-underline border-b border-white/10">
                    หน้าหลัก
                </a>
            </li>

            <!-- เกี่ยวกับหน่วยงาน -->
            <li class="w-full">
                <a href="{{ route('about') }}" 
                   class="block py-[15px] px-5 text-white no-underline border-b border-white/10">
                    เกี่ยวกับหน่วยงาน
                </a>
            </li>

            <!-- SCD Rankings -->
            <li class="w-full" x-data="{ dropdown1: false }">
                <button @click="dropdown1 = !dropdown1"
                        class="w-full text-left py-[15px] px-5 text-white border-b border-white/10">
                    SCD Rankings <span class="text-xs">▾</span>
                </button>
                <ul x-show="dropdown1" class="bg-black/20">
                    @forelse($publishedYears as $year)
                    <li>
                        <a href="{{ route('home', ['year' => $year->id]) }}" 
                           class="block py-3 pl-10 pr-5 text-white hover:bg-white/10 text-sm">
                            SCD {{ $year->year }}
                        </a>
                    </li>
                    @empty
                    <li>
                        <span class="block py-3 pl-10 pr-5 text-white/60 text-sm">ไม่มีข้อมูล</span>
                    </li>
                    @endforelse
                </ul>
            </li>

            <!-- รายงานผล SCD -->
            <li class="w-full" x-data="{ dropdown2: false }">
                <button @click="dropdown2 = !dropdown2"
                        class="w-full text-left py-[15px] px-5 text-white border-b border-white/10">
                    รายงานผล SCD <span class="text-xs">▾</span>
                </button>
                <ul x-show="dropdown2" class="bg-black/20">
                    @forelse($publishedYears as $year)
                        @if($year->report && $year->report->file_path)
                        <li>
                            <a href="{{ Storage::url($year->report->file_path) }}" 
                               target="_blank"
                               class="block py-3 pl-10 pr-5 text-white hover:bg-white/10 text-sm">
                                รายงาน SCD {{ $year->year }}
                            </a>
                        </li>
                        @endif
                    @empty
                    <li>
                        <span class="block py-3 pl-10 pr-5 text-white/60 text-sm">ไม่มีข้อมูล</span>
                    </li>
                    @endforelse
                </ul>
            </li>

            <!-- ประกาศ/คำสั่ง -->
            <li class="w-full" x-data="{ dropdown3: false }">
                <button @click="dropdown3 = !dropdown3"
                        class="w-full text-left py-[15px] px-5 text-white border-b border-white/10">
                    ประกาศ/คำสั่ง <span class="text-xs">▾</span>
                </button>
                <ul x-show="dropdown3" class="bg-black/20">
                    @forelse($publishedYears as $year)
                    <li>
                        <a href="{{ route('announcements', $year->id) }}" 
                           class="block py-3 pl-10 pr-5 text-white hover:bg-white/10 text-sm">
                            ประกาศ/คำสั่ง {{ $year->year }}
                        </a>
                    </li>
                    @empty
                    <li>
                        <span class="block py-3 pl-10 pr-5 text-white/60 text-sm">ไม่มีข้อมูล</span>
                    </li>
                    @endforelse
                </ul>
            </li>

            <!-- ติดต่อเรา -->
            <li class="w-full">
                <a href="{{ route('contact') }}" 
                   class="block py-[15px] px-5 text-white no-underline border-b border-white/10">
                    ติดต่อเรา
                </a>
            </li>
        </ul>
    </div>
</nav>