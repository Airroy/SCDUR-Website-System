@extends('admin.layouts.admin')

@section('header')
    <div class="flex items-center space-x-4 mb-3">
        <a href="{{ route('admin.years.announcements', $year) }}" class="text-gray-600 hover:text-gray-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-4xl font-bold text-gray-900">{{ $type === 'announcement' ? 'ประกาศ' : 'คำสั่ง' }} SCD {{ $year->year }}</h1>
            <div class="flex items-center space-x-2 text-lg text-gray-600 mt-1">
                <a href="{{ route('admin.scd-years.index') }}" class="hover:text-red-600">จัดการปี SCD</a>
                <span>/</span>
                <a href="{{ route('admin.years.manage', $year) }}" class="hover:text-red-600">
                    SCD {{ $year->year }}
                </a>
                <span>/</span>
                <a href="{{ route('admin.years.announcements', $year) }}" class="hover:text-red-600">
                    ประกาศ/คำสั่ง
                </a>
                <span>/</span>
                <span class="text-red-600 font-bold">{{ $type === 'announcement' ? 'ประกาศ' : 'คำสั่ง' }}</span>
            </div>
            <p class="text-lg text-gray-600 mt-1">จัดการ{{ $type === 'announcement' ? 'ประกาศ' : 'คำสั่ง' }}แบบหมวดหมู่หรือไฟล์เดี่ยว</p>
        </div>
    </div>
@endsection

@section('content')
    @livewire('backend.announcement-manager', ['year' => $year, 'categoryGroup' => $type], key($type.'-'.$year->id))
@endsection
