<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Speciality extends BaseModel
{
    use SoftDeletes , HasFactory, HasTranslations;
    protected $translatable = ['title'];
    protected $fillable = [
        'title',
        'level_id',
        'status'
    ];
    protected $casts = [];
    public function level()
    {
        return $this->belongsTo(Level::class);
    }
    public function lawyerSpecialities()
    {
        return $this->hasMany(LawyerSpeciality::class);
    }
    public function courtCases()
    {
        return $this->hasMany(CourtCase::class,'speciality_id');
    }

}
