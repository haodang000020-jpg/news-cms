<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'content',
        'featured_image',
        'seo_title',
        'seo_description',
        'status',
    ];
}