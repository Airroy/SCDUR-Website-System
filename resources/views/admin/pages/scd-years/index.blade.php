@extends('layouts.admin')

@section('title', 'จัดการปี SCD')

@section('header')
    <h2 class="text-4xl font-bold">จัดการข้อมูล SCD แต่ละปี</h2>
@endsection

@section('content')
    @livewire('backend.scd-year-manager')
@endsection
