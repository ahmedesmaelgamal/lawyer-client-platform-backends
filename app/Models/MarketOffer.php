<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketOffer extends BaseModel
{
    use SoftDeletes ,HasFactory;
    protected $fillable = [
        'image',
        'market_product_id',
        'from',
        'to',
        'status',
    ];
    protected $casts = [];

    public function marketProduct()
    {
        return $this->belongsTo(MarketProduct::class,'market_product_id','id');
    }

    public function ScopeActive($query)
    {
        return $query->where('status',StatusEnum::ACTIVE->value)->where('from', '<=', now())->where('to', '>=', now());
    }


}
