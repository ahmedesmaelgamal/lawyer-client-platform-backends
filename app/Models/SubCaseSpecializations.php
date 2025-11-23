<?php

namespace App\Models;

use Spatie\Translatable\HasTranslations;

class SubCaseSpecializations extends BaseModel
{
    protected $fillable = ['Case_Specializations_id', 'name'];
    protected $casts = [];

    use HasTranslations;

    public $translatable = [
        'name',
    ];

    public function caseSpecialization()
    {
        return $this->belongsTo(CaseSpecialization::class, 'Case_Specializations_id');
    }
}
