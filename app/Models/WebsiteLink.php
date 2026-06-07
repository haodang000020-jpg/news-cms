<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteLink extends Model
{
    protected $fillable = [
        'title',
        'url',
        'sort_order',
        'status',
    ];
}