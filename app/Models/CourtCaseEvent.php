<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourtCaseEvent extends BaseModel
{
    use SoftDeletes , HasFactory;
    protected $fillable = [
        'lawyer_id',
        'status',
        'price',
        'court_case_id',
        'refuse_reason_id',
        'refuse_note',
        'cancel_reason_id',
        'cancel_note',
        'transfer_lawyer_id',
        'transfer_lawyer_status',
        'transfer_client_status',
        'partner_id',
        'contribution_comment'

    ];
    protected $casts = [];

    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class,'lawyer_id','id');
    }
    public function courtCase()
    {
        return $this->belongsTo(CourtCase::class,'court_case_id');
    }
    public function refuseReason()
    {
        return $this->belongsTo(RefuseReason::class,'refuse_reason_id','id');
    }

    public function transferedLawyer()
    {
        return $this->belongsTo(Lawyer::class,'transfer_lawyer_id','id');
    }
    public function dues()
    {
        return $this->hasMany(CourtCaseDue::class,'court_case_event_id','id');
    }

}
