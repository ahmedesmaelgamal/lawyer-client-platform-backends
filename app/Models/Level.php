<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Level extends BaseModel
{
    use SoftDeletes , HasFactory , HasTranslations;
    public $translatable = ['title'];

    protected $fillable = [
        'title',
        'salary'
    ];
    protected $casts = [];
    public function lawyers()
    {
        return $this->hasMany(Lawyer::class);
    }
    public function specialities()
    {
        return $this->hasMany(Speciality::class);
    }

}
