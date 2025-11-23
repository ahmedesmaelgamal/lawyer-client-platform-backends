<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class FreeConsultationReplies extends BaseModel
{
    use SoftDeletes;
    protected $fillable = [
        'status',
        'free_consultation_request_id',
        'body',
    ];
    protected $casts = [];

}
