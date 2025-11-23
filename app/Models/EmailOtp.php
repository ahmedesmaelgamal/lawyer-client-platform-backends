<?php

namespace App\Models;

class EmailOtp extends BaseModel
{
    protected $fillable = [
        'email',
        'otp',
        'otp_expire',
        'is_verified',
    ];
    protected $casts = [];
}
