<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class CourtCaseFile extends BaseModel
{
    protected $fillable = [
        'case_id',
        'type',
        'file',
        'name'
    ];
    protected $casts = [];

}
