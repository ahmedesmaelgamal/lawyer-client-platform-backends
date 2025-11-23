<?php

namespace App\Models;

class CourtCaseUpdateFiles extends BaseModel
{
    protected $fillable = [
        'case_update_id',
        'type',
        'file',
        'name'
    ];

    protected $casts = [];

    public function courtCaseUpdate()
    {
        return $this->hasMany(CourtCaseUpdate::class,'case_update_id');
    }
}
