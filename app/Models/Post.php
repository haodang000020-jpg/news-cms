<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
protected $fillable = [
    'author_id',
    'title',
    'slug',
    'summary',
    'content',
    'featured_image',
    'status',
    'is_featured',
    'featured_order',
    'published_at',
    'views',
    'review_note',
];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'post_categories'
        );
    }

    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            'post_tags'
        );
    }
    public function attachments()
{
    return $this->hasMany(PostAttachment::class);
}
}