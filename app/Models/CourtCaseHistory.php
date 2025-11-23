<?php

namespace App\Models;

class CourtCaseHistory extends BaseModel
{
    protected $fillable = [
        'court_case_id',
        'status',
        'history',
        'user_id',
        'user_type',
        'extra'
    ];
    protected $casts = [];
}
