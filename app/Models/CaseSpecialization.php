<?php

namespace App\Models;

use Spatie\Translatable\HasTranslations;

class CaseSpecialization extends BaseModel
{
    use HasTranslations;
    protected $fillable = [
        'title',
        'status'
    ];
    protected $casts = [];
    protected $translatable = [
        'title',
    ];

    public function subCaseSpecializations()
    {
        return $this->hasMany(SubCaseSpecializations::class, 'Case_Specializations_id');
    }
}
