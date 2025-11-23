<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends BaseModel
{
    use SoftDeletes , HasFactory;
    protected $fillable = [
        'market_product_id',
        'lawyer_id',
        'status',
        'qty',
        'phone',
        'address',
        'total_price'
    ];
    protected $casts = [];
    public function lawyer(){
        return $this->belongsTo(Lawyer::class,'lawyer_id','id');
    }
    public function marketProduct(){
        return $this->belongsTo(MarketProduct::class,'market_product_id','id');
    }

}
