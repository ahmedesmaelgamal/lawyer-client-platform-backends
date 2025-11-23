<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class OfferPackage extends BaseModel
{
    use SoftDeletes, HasFactory,HasTranslations;
    protected $translatable = ['title'];

    protected $fillable = [
        'title',
        'number_of_days',
        'number_of_ads',
        'price',
        'discount',
        'status',
    ];
    protected $casts = [];
    public function lawyerPackages(){
        return $this->hasMany(LawyerPackage::class,'package_id','id');
    }
    public function ads(){
        return $this->hasMany(Ad::class,'package_id','id');
    }

}
