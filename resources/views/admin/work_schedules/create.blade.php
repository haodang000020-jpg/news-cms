@extends('admin.layouts.master')

@section('title', 'Thêm lịch công tác')

@section('page-title', 'Thêm lịch công tác')

@section('content')

@include('admin.work_schedules.form', [
    'action' => route('work-schedules.store'),
    'method' => 'POST',
    'schedule' => null,
])

@endsection