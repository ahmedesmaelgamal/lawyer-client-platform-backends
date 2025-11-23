<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class CommunityCategory extends BaseModel
{
    use SoftDeletes;
    use HasFactory;
    use HasTranslations;
    protected $translatable = ['title'];

    protected $fillable = [
//        'community_category_id',
        'title',
        'status',
    ];
    protected $casts = [];

    public function CommunitySubCategories()
    {
        return $this->hasMany(CommunitySubCategory::class, 'community_category_id');
    }
    public function scopeActive($query)
    {
        return $query->where('status', StatusEnum::ACTIVE->value);
    }
}
