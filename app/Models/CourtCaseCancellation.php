<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourtCaseCancellation extends BaseModel
{
    use SoftDeletes, HasFactory;
    protected $fillable = [
        'court_case_id',
        'accept_lawyer',
        'accept_client',
    ];
    protected $casts = [];
    public function courtCase()
    {
        return $this->belongsTo(CourtCase::class,'court_case_id');
    }

}

