<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rate extends BaseModel
{
    use SoftDeletes , HasFactory;
    protected $fillable = [
        'court_case_id',
        'from_user_id',
        'from_user_type',
        'to_user_id',
        'to_user_type',
        'rate',
        'reason_id',
        'comment'
    ];
    protected $casts = [];

}
