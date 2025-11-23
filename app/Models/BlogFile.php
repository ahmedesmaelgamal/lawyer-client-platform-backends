<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class BlogFile extends BaseModel
{
    use SoftDeletes;
    protected $fillable = [
        'blog_id',
        'file',
        'type'
    ];
    protected $casts = [];

}
