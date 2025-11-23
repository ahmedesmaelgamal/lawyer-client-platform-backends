<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class LawyerSpeciality extends BaseModel
{
    use SoftDeletes , HasFactory;
    protected $fillable = [
        'lawyer_id',
        'speciality_id',
    ];
    protected $casts = [];

    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class,'lawyer_id');
    }
    public function speciality()
    {
        return $this->belongsTo(Speciality::class,'speciality_id','id');
    }
}
