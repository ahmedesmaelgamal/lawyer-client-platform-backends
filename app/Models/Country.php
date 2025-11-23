<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Country extends BaseModel
{
    use SoftDeletes, HasFactory, HasTranslations;

    protected $translatable = ['title'];

    protected $fillable = [
        'title',
        'currency',
        'status'// enabled - disabled
    ];
    protected $casts = [];
    public function lawyers()
    {
        return $this->hasMany(Lawyer::class);
    }
    public function cities()
    {
        return $this->hasMany(City::class);
    }
    public function clients()
    {
        return $this->hasMany(Client::class);
    }

}
