<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends BaseModel
{
    use SoftDeletes , HasFactory;
    protected $fillable = [
        'title',
        'lawyer_id',
        'body',
        'user_id',
        'user_type',
        'model',
        'model_id',
        'extra',
    ];
    protected $casts = [
        'extra' => 'array',
    ];
}
