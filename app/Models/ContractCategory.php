<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class ContractCategory extends BaseModel
{
    use HasFactory,HasTranslations;
    protected $translatable = ['title'];

    protected $fillable = [
        'title'
    ];
    protected $casts = [
        'title' => 'array',
    ];
    public function contractFiles()
    {
        return $this->hasMany(ContractFile::class,'contract_category_id','id');
    }
}
