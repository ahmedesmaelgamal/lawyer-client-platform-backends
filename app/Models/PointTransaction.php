<?php

namespace App\Models;

class PointTransaction extends BaseModel
{
    protected $fillable = [
        'client_id',
        'points',
        'comment',
    ];
    protected $casts = [];

}
