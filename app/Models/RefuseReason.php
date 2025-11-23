<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class RefuseReason extends BaseModel
{
    use HasTranslations,HasFactory;

    protected $translatable = ["name"];
    protected $fillable = [
        'name',
        'type'
    ];
    protected $casts = [];

}
