<?php

namespace App\Models;

use App\Enums\OfficeRequestEnum;
use App\Enums\StatusEnum;
use App\Enums\UserTypeEnum;
use Illuminate\Console\Concerns\HasParameters;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Lawyer extends Authenticatable implements JWTSubject
{

    use SoftDeletes, HasFactory, HasParameters;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }


    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'image',
        'national_id',
        'city_id',
        'country_id',
        'phone',
        'country_code',
        'lawyer_id',
        'type',
        'level_id',
        'office_id'
    ];
    protected $casts = [];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class);
    }
    public function level()
    {
        return $this->belongsTo(Level::class);
    }
    public function lawyerSpecialities()
    {
        return $this->hasMany(LawyerSpeciality::class, 'lawyer_id');
    }
    public function LawyerReports()
    {
        return $this->hasMany(LawyerReport::class, 'lawyer_id')->where("status" , "active");
    }
    public function ads()
    {
        return $this->hasMany(Ad::class, 'lawyer_id', 'id');
    }
    public function courtCaseEvents()
    {
        return $this->hasMany(CourtCaseEvent::class, 'lawyer_id', 'id');
    }

    public function lawyerAbout()
    {
        return $this->hasOne(LawyerAbout::class, 'lawyer_id', 'id');
    }
    public function lawyerPackages()
    {
        return $this->hasMany(LawyerPackage::class, 'lawyer_id', 'id');
    }
    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class, 'user_id', 'id')
            ->where('user_type', UserTypeEnum::LAWYER->value);
    }
    public function lawyerTimes()
    {
        return $this->hasMany(LawyerTime::class, 'lawyer_id', 'id');
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id', 'id')
            ->where('user_type', UserTypeEnum::LAWYER->value);
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'lawyer_id', 'id');
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'user_id', 'id')
            ->where('user_type', UserTypeEnum::LAWYER->value);
    }

    public function blogComments()
    {
        return $this->hasMany(BlogComment::class, 'user_id', 'id')
            ->where('user_type', UserTypeEnum::LAWYER->value);
    }

    public function blogCommentReplies()
    {
        return $this->hasMany(BlogCommentReply::class, 'user_id', 'id')
            ->where('user_type', UserTypeEnum::LAWYER->value);
    }
    public function sosRequest()
    {
        return $this->hasOne(SOSRequest::class, 'lawyer_id');
    }


    public function scopeActive()
    {
        return $this->where('status', StatusEnum::ACTIVE->value);
    }

    public function rates()
    {
        return $this->hasMany(Rate::class, 'to_user_id', 'id')->where('to_user_type', UserTypeEnum::LAWYER->value);
    }

    public function officeRequest()
    {
        return $this->hasMany(OfficeRequest::class,'lawyer_id','id');
    }
    public function office()
    {
        return $this->belongsTo(Lawyer::class,'office_id','id');
    }
}
