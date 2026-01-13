@extends('admin.layouts.admin')

@section('back-url'){{ route('admin.years.manage', $year) }}@endsection

@section('header')
    <h2 class="text-4xl font-bold">รายงานผล SCD {{ $year->year }}</h2>
@endsection

@section('breadcrumbs')
    <a href="{{ route('admin.scd-years.index') }}" class="text-gray-600 hover:text-red-600 transition-colors">จัดการปี SCD</a>
    <span class="text-gray-400">/</span>
    <a href="{{ route('admin.years.manage', $year) }}" class="text-gray-600 hover:text-red-600 transition-colors">SCD {{ $year->year }}</a>
    <span class="text-gray-400">/</span>
    <span class="text-red-600 font-semibold">รายงานผล</span>
@endsection

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto">
        @livewire('backend.scd-report-manager', ['year' => $year], key('report-'.$year->id))
    </div>
</div>
@endsection
