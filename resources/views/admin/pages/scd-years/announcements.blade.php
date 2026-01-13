@extends('admin.layouts.admin')

@section('back-url'){{ route('admin.years.manage', $year) }}@endsection

@section('header')
    <h2 class="text-4xl font-bold">ประกาศ/คำสั่ง SCD {{ $year->year }}</h2>
@endsection

@section('breadcrumbs')
    <a href="{{ route('admin.scd-years.index') }}" class="text-gray-600 hover:text-red-600 transition-colors">จัดการปี SCD</a>
    <span class="text-gray-400">/</span>
    <a href="{{ route('admin.years.manage', $year) }}" class="text-gray-600 hover:text-red-600 transition-colors">SCD {{ $year->year }}</a>
    <span class="text-gray-400">/</span>
    <span class="text-red-600 font-semibold">ประกาศ/คำสั่ง</span>
@endsection

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Two Main Category Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-4xl">
        <!-- Announcement Card -->
        <a href="{{ route('admin.years.announcements.category', ['year' => $year, 'type' => 'announcement']) }}" 
           class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 p-6 group flex items-center gap-4">
            <!-- Icon -->
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-orange-200 transition-colors">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                </svg>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
                <h3 class="text-xl font-bold text-gray-900 mb-1">ประกาศ</h3>
                <p class="text-base text-gray-500">จัดการประกาศแบบหมวดหมู่หรือไฟล์เดี่ยว</p>
            </div>

            <!-- Arrow -->
            <svg class="w-5 h-5 text-gray-400 group-hover:text-orange-600 group-hover:translate-x-1 transition-all flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>

        <!-- Order Card -->
        <a href="{{ route('admin.years.announcements.category', ['year' => $year, 'type' => 'order']) }}" 
           class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 p-6 group flex items-center gap-4">
            <!-- Icon -->
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-blue-200 transition-colors">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
                <h3 class="text-xl font-bold text-gray-900 mb-1">คำสั่ง</h3>
                <p class="text-base text-gray-500">จัดการคำสั่งแบบหมวดหมู่หรือไฟล์เดี่ยว</p>
            </div>

            <!-- Arrow -->
            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-1 transition-all flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
        </div>
    </div>
</div>
@endsection
