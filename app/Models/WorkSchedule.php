<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkSchedule extends Model
{
    protected $fillable = [
        'work_date',
        'start_time',
        'end_time',
        'title',
        'location',
        'chairperson',
        'participants',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'work_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'status' => 'boolean',
    ];
}