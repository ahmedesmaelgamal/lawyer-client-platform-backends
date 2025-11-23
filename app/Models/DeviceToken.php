<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceToken extends BaseModel
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'user_type',
        'token',
        'device_type',
        'mac_address'
    ];
    protected $casts = [];

}
