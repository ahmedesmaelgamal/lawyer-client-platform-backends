<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends BaseModel
{
    use SoftDeletes , HasFactory;
    protected $fillable = [
        'key',
        'value'
    ];
    protected $casts = [];

}
