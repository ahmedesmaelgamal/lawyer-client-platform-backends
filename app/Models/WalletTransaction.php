<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class WalletTransaction extends BaseModel
{
    use SoftDeletes , HasFactory;
    protected $fillable = [
        'user_id',
        'user_type',
        'credit',
        'debit',
        'comment',
        'case_id',
        'model_id',
        'model_type',
    ];
    protected $casts = [
        "user_id" => "integer",
    ];


}
