<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class LawyerPackage extends BaseModel
{
    use SoftDeletes ,HasFactory;
    protected $fillable = [
        'lawyer_id',
        'package_id',
        'start_date',
        'end_date',
        'number_of_bumps',
        'status',
        'is_expired'
    ];
    protected $casts = [];
    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class,'lawyer_id','id');
    }
    public function offerPackage()
    {
        return $this->belongsTo(OfferPackage::class,'package_id','id');
    }
    public function ads()
    {
        return $this->hasMany(Ad::class,'package_id','id');
    }
}
