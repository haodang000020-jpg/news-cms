<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostAttachment extends Model
{
    protected $fillable = [
        'post_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function getFileSizeTextAttribute()
    {
        if ($this->file_size >= 1048576) {
            return round($this->file_size / 1048576, 2) . ' MB';
        }

        if ($this->file_size >= 1024) {
            return round($this->file_size / 1024, 2) . ' KB';
        }

        return $this->file_size . ' bytes';
    }
}