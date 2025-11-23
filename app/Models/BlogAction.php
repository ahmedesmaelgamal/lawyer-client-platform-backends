<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class BlogAction extends BaseModel
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'user_type',
        'action'
    ];
    protected $casts = [];

}
