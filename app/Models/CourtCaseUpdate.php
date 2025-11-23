<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourtCaseUpdate extends BaseModel
{
    use SoftDeletes , HasFactory;
    protected $fillable = [
        'title',
        'court_case_id',
        'details',
        'date',
    ];
    protected $casts = [];
    public function courtCase()
    {
        return $this->belongsTo(CourtCase::class,'court_case_id');
    }

    public function courtCaseUpdateFiles()
    {
        return $this->hasMany(CourtCaseUpdateFiles::class,'case_update_id','id');
    }

}
