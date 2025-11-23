<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasPermissions;

class SOSRequest extends BaseModel
{
    protected $table = 's_o_s__requests';
    use SoftDeletes,HasFactory,HasPermissions;
    protected $fillable = [
        'problem',
        'phone',
        'address',
        'lat',
        'long',
        'status',
        'client_id',
        'voice',
        'lawyer_id',
    ];
    protected $casts = [];

    public function client()
    {
        return $this->belongsTo(Client::class,'client_id');

    }
    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class ,'lawyer_id',);

    }
}
