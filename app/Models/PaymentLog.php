<?php

namespace App\Models;

class PaymentLog extends BaseModel
{
    protected $fillable = [
        'payment_id',
        'log',
        'payment_status',
        'payment_amount',
    ];

    protected $casts = [];

}
