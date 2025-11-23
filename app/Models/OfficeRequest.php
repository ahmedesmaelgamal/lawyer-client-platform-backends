<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class OfficeRequest extends BaseModel
{
    use SoftDeletes;

    protected $table = 'office_requests';
    protected $fillable = [
        'office_id',
        'lawyer_id',
        'status'
    ];
    protected $casts = [];

    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class, 'lawyer_id', 'id');
    }
    public function office()
    {
        return $this->belongsTo(Lawyer::class,'office_id','id');
    }
}
