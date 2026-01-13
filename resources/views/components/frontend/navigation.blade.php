@props(['publishedYears' => []])

<nav class="bg-red-700 sticky w-full z-20 top-0 start-0 border-b border-red-800 shadow-md" x-data="{ mobileMenuOpen: false, scdRankingOpen: false, reportOpen: false, announcementOpen: false }">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
    <!-- Mobile menu button -->
    <button 
        @click="mobileMenuOpen = !mobileMenuOpen"
        type="button" 
        class="inline-flex items-center p-2 w-10 h-10 justify-center text-white rounded-lg md:hidden hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-300"
    >
        <span class="sr-only">Open main menu</span>
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>
    
    <div class="hidden w-full md:block md:w-auto" :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen}" id="navbar-dropdown">
      <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-red-800 rounded-lg bg-red-600 md:space-x-0 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-red-700">
        <!-- หน้าหลัก -->
        <li class="md:flex md:items-stretch">
          <a href="{{ route('home') }}" class="flex items-center py-2 px-3 md:px-6 text-white md:hover:bg-red-800 transition-colors h-full">หน้าหลัก</a>
        </li>
        
        <!-- เกี่ยวกับหน่วยงาน -->
        <li class="md:flex md:items-stretch">
          <a href="{{ route('about') }}" class="flex items-center py-2 px-3 md:px-6 text-white md:hover:bg-red-800 transition-colors h-full">เกี่ยวกับหน่วยงาน</a>
        </li>
        
        <!-- SCD Ranking Dropdown -->
        <li class="relative group md:flex md:items-stretch">
            <button 
                class="flex items-center justify-between w-full py-2 px-3 md:px-6 text-white md:hover:bg-red-800 transition-colors h-full"
            >
              SCD Ranking 
              <svg class="w-4 h-4 ms-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
              </svg>
            </button>
            <div class="hidden group-hover:block absolute z-10 bg-white border border-gray-200 rounded-lg shadow-lg w-44 mt-2">
                <ul class="p-2 text-sm text-gray-700 font-medium">
                  @forelse($publishedYears as $year)
                  <li>
                    <a href="{{ route('home', ['year' => $year->id]) }}" class="block p-2 hover:bg-gray-100 hover:text-red-600 rounded">SCD {{ $year->year }}</a>
                  </li>
                  @empty
                  <li class="p-2 text-gray-500">ไม่มีข้อมูล</li>
                  @endforelse
                </ul>
            </div>
        </li>
        
        <!-- รายงานผล SCD Dropdown -->
        <li class="relative group md:flex md:items-stretch">
            <button 
                class="flex items-center justify-between w-full py-2 px-3 md:px-6 text-white md:hover:bg-red-800 transition-colors h-full"
            >
              รายงานผล SCD 
              <svg class="w-4 h-4 ms-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
              </svg>
            </button>
            <div class="hidden group-hover:block absolute z-10 bg-white border border-gray-200 rounded-lg shadow-lg w-44 mt-2">
                <ul class="p-2 text-sm text-gray-700 font-medium">
                  @forelse($publishedYears as $year)
                    @if($year->report && $year->report->file_path)
                    <li>
                      <a href="{{ Storage::url($year->report->file_path) }}" target="_blank" class="block p-2 hover:bg-gray-100 hover:text-red-600 rounded">รายงาน SCD {{ $year->year }}</a>
                    </li>
                    @endif
                  @empty
                  <li class="p-2 text-gray-500">ไม่มีข้อมูล</li>
                  @endforelse
                </ul>
            </div>
        </li>
        
        <!-- ประกาศ/คำสั่ง Dropdown -->
        <li class="relative group md:flex md:items-stretch">
            <button 
                class="flex items-center justify-between w-full py-2 px-3 md:px-6 text-white md:hover:bg-red-800 transition-colors h-full"
            >
              ประกาศ/คำสั่ง 
              <svg class="w-4 h-4 ms-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
              </svg>
            </button>
            <div class="hidden group-hover:block absolute z-10 bg-white border border-gray-200 rounded-lg shadow-lg w-44 mt-2">
                <ul class="p-2 text-sm text-gray-700 font-medium">
                  @forelse($publishedYears as $year)
                  <li>
                    <a href="{{ route('announcements', $year->id) }}" class="block p-2 hover:bg-gray-100 hover:text-red-600 rounded">ประกาศ/คำสั่ง {{ $year->year }}</a>
                  </li>
                  @empty
                  <li class="p-2 text-gray-500">ไม่มีข้อมูล</li>
                  @endforelse
                </ul>
            </div>
        </li>
        
        <!-- ติดต่อเรา -->
        <li class="md:flex md:items-stretch">
          <a href="{{ route('contact') }}" class="flex items-center py-2 px-3 md:px-6 text-white md:hover:bg-red-800 transition-colors h-full">ติดต่อเรา</a>
        </li>
      </ul>
    </div>
  </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" 
         x-transition
         class="md:hidden bg-red-500 border-t border-red-700"
         style="display: none;"
         @click.away="mobileMenuOpen = false">
        <div class="py-2">
            <a href="{{ route('home') }}" class="block text-white hover:bg-red-700 px-4 py-3 text-base font-medium">
                หน้าหลัก
            </a>
            <a href="{{ route('about') }}" class="block text-white hover:bg-red-700 px-4 py-3 text-base font-medium">
                เกี่ยวกับหน่วยงาน
            </a>
            
            <div class="px-4 py-2 border-t border-red-700">
                <p class="text-xs font-semibold text-red-200 uppercase tracking-wider mb-1">SCD Ranking</p>
                @forelse($publishedYears as $year)
                    <a href="{{ route('home', ['year' => $year->id]) }}" class="block text-white hover:bg-red-700 px-2 py-2 text-sm">
                        SCD {{ $year->year }}
                    </a>
                @empty
                    <span class="block text-red-200 px-2 py-1 text-sm">ไม่มีข้อมูล</span>
                @endforelse
            </div>
            
            <div class="px-4 py-2 border-t border-red-700">
                <p class="text-xs font-semibold text-red-200 uppercase tracking-wider mb-1">รายงานผล SCD</p>
                @forelse($publishedYears as $year)
                    @if($year->report && $year->report->file_path)
                        <a href="{{ Storage::url($year->report->file_path) }}" target="_blank" class="block text-white hover:bg-red-700 px-2 py-2 text-sm">
                            รายงาน SCD {{ $year->year }}
                        </a>
                    @endif
                @empty
                    <span class="block text-red-200 px-2 py-1 text-sm">ไม่มีข้อมูล</span>
                @endforelse
            </div>
            
            <div class="px-4 py-2 border-t border-red-700">
                <p class="text-xs font-semibold text-red-200 uppercase tracking-wider mb-1">ประกาศ/คำสั่ง</p>
                @forelse($publishedYears as $year)
                    <a href="{{ route('announcements', $year->id) }}" class="block text-white hover:bg-red-700 px-2 py-2 text-sm">
                        ประกาศ/คำสั่ง {{ $year->year }}
                    </a>
                @empty
                    <span class="block text-red-200 px-2 py-1 text-sm">ไม่มีข้อมูล</span>
                @endforelse
            </div>
            
            <a href="{{ route('contact') }}" class="block text-white hover:bg-red-700 px-4 py-3 text-base font-medium border-t border-red-700">
                ติดต่อเรา
            </a>
        </div>
    </div>
</nav>
