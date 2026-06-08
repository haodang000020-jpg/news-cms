<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkSchedule;
use Illuminate\Http\Request;

class WorkScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = WorkSchedule::query();

        if ($request->filled('from_date')) {
            $query->whereDate('work_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('work_date', '<=', $request->to_date);
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('location', 'like', '%' . $keyword . '%')
                    ->orWhere('chairperson', 'like', '%' . $keyword . '%')
                    ->orWhere('participants', 'like', '%' . $keyword . '%');
            });
        }

        $schedules = $query
            ->orderByDesc('work_date')
            ->orderBy('start_time')
            ->paginate(15)
            ->withQueryString();

        return view('admin.work_schedules.index', compact('schedules'));
    }

    public function create()
    {
        return view('admin.work_schedules.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'work_date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'title' => 'required|max:500',
            'location' => 'nullable|max:255',
            'chairperson' => 'nullable|max:255',
            'participants' => 'nullable',
            'status' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        WorkSchedule::create([
            'work_date' => $request->work_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'title' => $request->title,
            'location' => $request->location,
            'chairperson' => $request->chairperson,
            'participants' => $request->participants,
            'status' => $request->boolean('status'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()
            ->route('work-schedules.index')
            ->with('success', 'Thêm lịch công tác thành công');
    }

    public function edit(WorkSchedule $workSchedule)
    {
        return view('admin.work_schedules.edit', compact('workSchedule'));
    }

    public function update(Request $request, WorkSchedule $workSchedule)
    {
        $request->validate([
            'work_date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'title' => 'required|max:500',
            'location' => 'nullable|max:255',
            'chairperson' => 'nullable|max:255',
            'participants' => 'nullable',
            'status' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $workSchedule->update([
            'work_date' => $request->work_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'title' => $request->title,
            'location' => $request->location,
            'chairperson' => $request->chairperson,
            'participants' => $request->participants,
            'status' => $request->boolean('status'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()
            ->route('work-schedules.index')
            ->with('success', 'Cập nhật lịch công tác thành công');
    }

    public function destroy(WorkSchedule $workSchedule)
    {
        $workSchedule->delete();

        return redirect()
            ->route('work-schedules.index')
            ->with('success', 'Xóa lịch công tác thành công');
    }
}