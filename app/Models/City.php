<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class City extends BaseModel
{
    use SoftDeletes , HasFactory , HasTranslations;
    protected $translatable = ['title'];

    protected $fillable = [
        'title',
        'country_id'
    ];
    protected $casts = [];
    public function lawyers()
    {
        return $this->hasMany(Lawyer::class);
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function clients()
    {
        return $this->hasMany(Client::class);
    }

}
