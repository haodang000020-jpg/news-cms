<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorkScheduleController extends Controller
{
    public function index(Request $request)
    {
        $fromDate = $request->from_date
            ? Carbon::parse($request->from_date)
            : now()->startOfWeek();

        $toDate = $request->to_date
            ? Carbon::parse($request->to_date)
            : now()->endOfWeek();

        $schedules = WorkSchedule::where('status', true)
            ->whereDate('work_date', '>=', $fromDate)
            ->whereDate('work_date', '<=', $toDate)
            ->orderBy('work_date')
            ->orderBy('start_time')
            ->orderBy('sort_order')
            ->get()
            ->groupBy(function ($item) {
                return $item->work_date->format('Y-m-d');
            });

        return view('frontend.work_schedules.index', compact(
            'schedules',
            'fromDate',
            'toDate'
        ));
    }
}