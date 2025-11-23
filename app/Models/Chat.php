<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends BaseModel
{
    use SoftDeletes;
    protected $fillable = [
        'from_id',
        'to_id',
        'sender_type',
        'receiver_type',
        'message',
        'message_type',
        'file'
    ];
    protected $casts = [];

}
