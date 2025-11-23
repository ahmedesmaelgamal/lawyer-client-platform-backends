<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Testing\Fluent\Concerns\Has;
use Spatie\Translatable\HasTranslations;

class CourtCaseLevel extends BaseModel
{
    use SoftDeletes, HasFactory;
    use HasTranslations;
    protected $fillable = [
        'title',
        'status',
        'court_case_id',
    ];

    protected $translatable = [
        'title',
    ];
    protected $casts = [];
    public function courtCase(){
        return $this->hasMany(CourtCase::class, 'court_case_level_id');
    }
}
