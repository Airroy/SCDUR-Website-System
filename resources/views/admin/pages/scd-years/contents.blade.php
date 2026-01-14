@extends('layouts.admin')

@section('back-url'){{ route('admin.years.manage', $year) }}@endsection

@section('header')
    <h2 class="text-4xl font-bold">Content Section SCD {{ $year->year }}</h2>
@endsection

@section('breadcrumbs')
    <a href="{{ route('admin.scd-years.index') }}" class="text-gray-600 hover:text-red-600 transition-colors">จัดการปี SCD</a>
    <span class="text-gray-400">/</span>
    <a href="{{ route('admin.years.manage', $year) }}" class="text-gray-600 hover:text-red-600 transition-colors">SCD {{ $year->year }}</a>
    <span class="text-gray-400">/</span>
    <span class="text-red-600 font-semibold">Content Section</span>
@endsection

@section('content')
    @livewire('backend.content-section-manager', ['year' => $year], key('content-'.$year->id))
@endsection
