<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LawyerReport extends Model
{
    use HasFactory;
    protected $table = "lawyer_reports";
    protected  $fillable = ["client_id" , "lawyer_id" , "body" , "status"];


    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class);
    }

}
