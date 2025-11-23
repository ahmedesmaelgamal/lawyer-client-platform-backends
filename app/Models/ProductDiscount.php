<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ProductDiscount extends BaseModel
{
    use SoftDeletes;
    protected $fillable = [
        'discount',
        'product_id',
        'from',
        'to'
    ];
    protected $casts = [];

    public function product()
    {
        return $this->belongsTo(MarketProduct::class,'product_id','id');
    }
}
