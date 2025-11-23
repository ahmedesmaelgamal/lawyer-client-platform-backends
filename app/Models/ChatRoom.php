<?php

namespace App\Models;

use Illuminate\Support\Str;

class ChatRoom extends BaseModel
{
    protected $fillable = [
        'id',
        'uuid',
        'lawyer_id',
        'client_id',
    ];
    protected $casts = [];

    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class,'lawyer_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class,'client_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

}
