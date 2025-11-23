<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles ;

    protected $fillable = [
        'name',
        'user_name',
        'code',
        'email',
        'password',
        'role_id',
        'image',
        "twofa_qr",
        "twofa_secret",
        "twofa_verify"
    ];
}//end class
