<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class LawyerTime extends BaseModel
{
    use SoftDeletes , HasFactory;
    protected $fillable = [
        'lawyer_id',
        'day',
        'from',
        'to',
        'status'
    ];
    protected $casts = [
        'lawyer_id' => 'integer',
    ];
    public function lawyer(){
        return $this->belongsTo(Lawyer::class,'lawer_id','id');
    }

    public function scopeActive()
    {
        return $this->where('status',StatusEnum::ACTIVE->value);
    }
}
