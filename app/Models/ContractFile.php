<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class ContractFile extends BaseModel
{
    use HasTranslations , HasFactory;

    protected $translatable = ['file_name'];

    protected $fillable = [
        'file_path',
        'file_name',
        'file_extension',
        'contract_category_id'
    ];
    protected $casts = [
        'file_name' => 'array',
    ];

    public function contractCategory()
    {
        return $this->belongsTo(ContractCategory::class, 'contract_category_id');
    }
}
