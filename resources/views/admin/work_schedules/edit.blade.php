@extends('admin.layouts.master')

@section('title', 'Sửa lịch công tác')

@section('page-title', 'Sửa lịch công tác')

@section('content')

@include('admin.work_schedules.form', [
    'action' => route('work-schedules.update', $workSchedule->id),
    'method' => 'PUT',
    'schedule' => $workSchedule,
])

@endsection