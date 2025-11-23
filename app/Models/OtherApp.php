<?php

namespace App\Models;

class OtherApp extends BaseModel
{
    protected $fillable = [
        'name',
        'android_url',
        'ios_url',
        'icon',
        'status',
    ];
    protected $casts = [];
}
