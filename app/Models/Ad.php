<?php

namespace App\Models;

use App\Enums\AdConfirmationEnum;
use App\Enums\StatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasPermissions;

class Ad extends BaseModel
{
    use SoftDeletes, HasFactory, HasPermissions;

    protected $fillable = [
        'lawyer_id',
        'package_id',
        'status',
        'from_date',
        'to_date',
//        'link',
        'refuse_reason',
        'image',
        'ad_confirmation',
        'refuse_reason'
    ];
    protected $casts = [];

    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class, 'lawyer_id', 'id');
    }

    public function offerPackage()
    {
        return $this->belongsTo(LawyerPackage::class, 'package_id', 'id');
    }

    public function scopeActive()
    {
        return $this->where('status', StatusEnum::ACTIVE->value)
            ->where('ad_confirmation', AdConfirmationEnum::CONFIRMED->value)
            ->whereDate('from_date', '<=', now())->whereDate('to_date', '>=', now());
    }
}
