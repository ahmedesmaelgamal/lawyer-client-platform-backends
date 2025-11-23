<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class LawyerAbout extends BaseModel
{
    use SoftDeletes, HasFactory;
    protected $fillable = [
        'lawyer_id',
        'about',
        'consultation_fee',
        'attorney_fee',
        'office_address',
        'success_case',
        'failed_case',
        'public_work',
        'lat',
        'lng'
    ];
    protected $casts = [];
    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class, 'lawyer_id', 'id');
    }
}
