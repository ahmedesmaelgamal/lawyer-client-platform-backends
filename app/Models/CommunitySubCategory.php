<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class CommunitySubCategory extends BaseModel
{
    use SoftDeletes;
    use HasFactory;
    use HasTranslations;
    protected $translatable = ['title'];

    protected $fillable = [
        'community_category_id',
        'title',
        'status',
    ];
    protected $casts = [];

    public function communityCategory()
    {
        return $this->belongsTo(CommunityCategory::class, 'community_category_id');
    }

    public function CommunityServices()
    {
        return $this->hasMany(CommunityService::class, 'community');
    }

    public function scopeActive($query)
    {
        return $query->where('status', StatusEnum::ACTIVE->value);
    }
}
