<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class CommunityService extends BaseModel
{
    use SoftDeletes;
    use HasFactory;
    use HasTranslations;

    protected $translatable = ['body'];


    protected $fillable = [
        'community_sub_category_id',
        'body',
        'status',
    ];
    protected $casts = [];

    public function communitySubCategory()
    {
        return $this->belongsTo(CommunitySubCategory::class, 'community_sub_category_id');
    }
    public function scopeActive($query)
    {
        return $query->where('status', StatusEnum::ACTIVE->value);
    }

}
