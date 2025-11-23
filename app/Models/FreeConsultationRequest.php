<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class FreeConsultationRequest extends BaseModel
{
    use SoftDeletes;
    protected $fillable = [
        'status',
        'client_id',
        'body',
    ];
    protected $casts = [];

}
