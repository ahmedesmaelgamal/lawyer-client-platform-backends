<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourtCaseDue extends BaseModel
{
    use SoftDeletes ,HasFactory;
    protected $fillable = [
        'title',
        'from_user_id',
        'to_user_id',
        'from_user_type',
        'to_user_type',
        'court_case_id',
        'court_case_event_id',
        'date',
        'price',
        'paid'
    ];
    protected $casts = [];
    public function courtCase()
    {
        return $this->belongsTo(CourtCase::class,'court_case_id');
    }
    public function courtCaseEvent()
    {
        return $this->belongsTo(CourtCaseEvent::class,'court_case_event_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class,'from_user_id');
    }
    public function fromLawyer(){
        return $this->belongsTo(Lawyer::class,'from_user_id');
    }
    public function toLawyer(){
        return $this->belongsTo(Lawyer::class,'to_user_id');
    }
}
