<?php

namespace App\Models;

class WithdrawRequest extends BaseModel
{
    protected $fillable = [
        'user_id',
        'user_type',
        'amount',
        'status',
        'payment_method',
        'payment_key',
        'reject_reason',
    ];
    protected $casts = [];

    public function user()
    {
        return $this->user_type == 'lawyer' ? $this->belongsTo(Lawyer::class, 'user_id') : $this->belongsTo(Client::class, 'user_id');
    }

}
