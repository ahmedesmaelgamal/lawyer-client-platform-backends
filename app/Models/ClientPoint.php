<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientPoint extends BaseModel
{
    use SoftDeletes , HasFactory;
    protected $fillable = [
        'client_id',
        'points',
        'commercial_code',
        'entered_with_code',
    ];
    protected $casts = [];
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
    public function scopeActive($query)
    {
        return $query->where('status', StatusEnum::ACTIVE->value);
    }


}
