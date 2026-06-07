<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
     protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'image',
        'sort_order',
        'status'
    ];

   public function parent()
{
    return $this->belongsTo(
        Category::class,
        'parent_id'
    );
}

   public function children()
{
    return $this->hasMany(
        Category::class,
        'parent_id'
    )->where('status', true)
     ->orderBy('sort_order');
}
    public function posts()
{
    return $this->belongsToMany(
        Post::class,
        'post_categories'
    );
}
}
