<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class MarketProduct extends BaseModel
{
    use SoftDeletes, HasFactory, HasTranslations;

    public $translatable = ['title', 'description'];
    protected $fillable = [
        'title',
        'image',
        'description',
        'location',
        'stock',
        'price',
        'discount',
        'market_product_category_id',
        'status'
    ];
    protected $casts = [];

    public function marketProductCategory()
    {
        return $this->belongsTo(MarketProductCategory::class, 'market_product_category_id');
    }

    public function productDiscount()
    {
        return $this->hasOne(ProductDiscount::class, 'product_id', 'id')
            ->where('from', '<=', now())
            ->where('to', '>=', now());
    }

    public function marketOffers()
    {
        return $this->hasMany(MarketOffer::class, 'market_product_id');
    }
    public function order(){
        return $this->hasOne(Order::class,'p','id');
    }
}
