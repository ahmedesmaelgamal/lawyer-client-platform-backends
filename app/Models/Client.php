<?php

namespace App\Models;

use App\Enums\ReactionEnum;
use App\Enums\UserTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasPermissions;

class Client extends Authenticatable implements JWTSubject
{
    use SoftDeletes , HasFactory , HasPermissions;
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
        'image',
        'password',
        'national_id',
        'phone',
        'country_code',
        'points',
        'city_id',
        'country_id',
        'status',
        'wallet'
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class,  'country_id');
    }
    public function courtCases(){
        return $this->hasMany(CourtCase::class,'client_id','id');
    }
    protected $casts = [];


    public function walletTransactions(){
        return $this->hasMany(WalletTransaction::class,'user_id','id')
        ->where('user_type', UserTypeEnum::CLIENT->value);
    }

    public function blogComments(){
        return $this->hasMany(BlogComment::class,'user_id','id')
        ->where('user_type', UserTypeEnum::CLIENT->value);
    }


    public function blogReactions()
    {
        return $this->hasMany(BlogReaction::class, 'user_id', 'id')
            ->where('user_type', UserTypeEnum::CLIENT->value);
    }

    public function blogLikes()
    {
        return $this->blogReactions()->where('reaction', ReactionEnum::LIKE->value);
    }

    public function blogDislikes()
    {
        return $this->blogReactions()->where('reaction', ReactionEnum::DISLIKE->value);
    }


    public function notifications(){
        return $this->hasMany(Notification::class,'user_id','id')
        ->where('user_type', UserTypeEnum::CLIENT->value);
    }

    public function rates()
    {
        return $this->hasMany(Rate::class, 'to_user_id', 'id')->where('to_user_type', UserTypeEnum::CLIENT->value);
    }

    public function sosRequest()
    {
        return $this->hasOne(SOSRequest::class,'client_id');
    }

    public function walletFromat()
    {
        return number_format($this->wallet, 2);
    }

}
