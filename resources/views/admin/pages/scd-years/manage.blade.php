@extends('admin.layouts.admin')

@section('back-url'){{ route('admin.scd-years.index') }}@endsection

@section('header')
    <h2 class="text-4xl font-bold">จัดการข้อมูล SCD {{ $year->year }}</h2>
@endsection

@section('breadcrumbs')
    <a href="{{ route('admin.scd-years.index') }}" class="text-gray-600 hover:text-red-600 transition-colors">จัดการปี SCD</a>
    <span class="text-gray-400">/</span>
    <span class="text-red-600 font-semibold">SCD {{ $year->year }}</span>
@endsection

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Sub-Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Card 1: รายงานผล SCD -->
        <a href="{{ route('admin.years.reports', $year) }}" class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 p-4 group flex items-center gap-4">
            <!-- Icon -->
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-purple-200 transition-colors">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
                <h3 class="text-xl font-bold text-gray-900 mb-1">รายงานผล SCD</h3>
                <div class="flex items-center gap-2">
                    <p class="text-base text-gray-500">อัปโหลดไฟล์ PDF รายงานผล (1 ไฟล์ต่อปี)</p>
                    @if($year->report)
                        <span class="px-2 py-0.5 text-sm font-semibold rounded-full bg-green-100 text-green-700">มีไฟล์</span>
                    @endif
                </div>
            </div>

            <!-- Arrow -->
            <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600 group-hover:translate-x-1 transition-all flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>

        <!-- Card 2: Slider Banner -->
        <a href="{{ route('admin.years.banners', $year->id) }}" class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 p-4 group flex items-center gap-4">
            <!-- Icon -->
            <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-pink-200 transition-colors">
                <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
                <h3 class="text-xl font-bold text-gray-900 mb-1">Slider Banner</h3>
                <div class="flex items-center gap-2">
                    <p class="text-base text-gray-500">จัดการรูปภาพสไลด์แบนเนอร์พร้อมลิงค์หรือไฟล์ PDF</p>
                    @if($year->banners->count() > 0)
                        <span class="px-2 py-0.5 text-sm font-semibold rounded-full bg-purple-100 text-purple-700">{{ $year->banners->count() }} สไลด์</span>
                    @endif
                </div>
            </div>

            <!-- Arrow -->
            <svg class="w-5 h-5 text-gray-400 group-hover:text-pink-600 group-hover:translate-x-1 transition-all flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>

        <!-- Card 3: ประกาศ/คำสั่ง -->
        <a href="{{ route('admin.years.announcements', $year->id) }}" class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 p-4 group flex items-center gap-4">
            <!-- Icon -->
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-orange-200 transition-colors">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                </svg>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
                <h3 class="text-xl font-bold text-gray-900 mb-1">ประกาศ/คำสั่ง</h3>
                <p class="text-base text-gray-500">จัดการประกาศและคำสั่งแบบหมวดหมู่หรือไฟล์เดี่ยว</p>
            </div>

            <!-- Arrow -->
            <svg class="w-5 h-5 text-gray-400 group-hover:text-orange-600 group-hover:translate-x-1 transition-all flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>

        <!-- Card 4: Content Section -->
        <a href="{{ route('admin.years.contents', $year->id) }}" class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 p-4 group flex items-center gap-4">
            <!-- Icon -->
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-green-200 transition-colors">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
                <h3 class="text-xl font-bold text-gray-900 mb-1">Content Section</h3>
                <div class="flex items-center gap-2">
                    <p class="text-base text-gray-500">จัดการเนื้อหาหลักพร้อมรูปภาพหัวข้อและไฟล์เอกสาร</p>
                    @php
                        $contentCount = $year->contentNodes->where('category_group', 'content_section')->where('parent_id', null)->count();
                    @endphp
                    @if($contentCount > 0)
                        <span class="px-2 py-0.5 text-sm font-semibold rounded-full bg-green-100 text-green-700">{{ $contentCount }} หัวข้อ</span>
                    @endif
                </div>
            </div>

            <!-- Arrow -->
            <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 group-hover:translate-x-1 transition-all flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
        </div>
    </div>
</div>
@endsection
