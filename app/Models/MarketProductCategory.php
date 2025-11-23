<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class MarketProductCategory extends BaseModel
{
    use SoftDeletes , HasFactory , HasTranslations;
    public $translatable = ['title'];

    protected $fillable = [
        'title'
    ];
    protected $casts = [];

    public function marketProduct()
    {
        return $this->hasMany(MarketProduct::class, 'market_product_category_id');
    }
}
