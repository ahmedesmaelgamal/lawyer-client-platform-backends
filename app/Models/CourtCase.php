<?php

namespace App\Models;

use App\Enums\EventStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourtCase extends BaseModel
{
    use SoftDeletes, HasFactory;
    protected $fillable = [
        'type',
        'title',
        'client_id',
        'speciality_id',
        'case_estimated_price',
        'case_number',
        'details',
        'status',
        'case_final_price',
        'court_case_level_id',
        'case_speciality_id',
        'sub_case_speciality_id'
    ];
    protected $casts = [
        'seen'=> 'integer',
    ];
    public function speciality()
    {
        return $this->belongsTo(Speciality::class, 'speciality_id');
    }
    public function courtCaseEvents()
    {
        return $this->hasMany(CourtCaseEvent::class, 'court_case_id', 'id');
    }
    public function courtCaseUpdates()
    {
        return $this->hasMany(CourtCaseUpdate::class, 'court_case_id');
    }
    public function courtCaseCancellations()
    {
        return $this->hasMany(CourtCaseCancellation::class, 'court_case_id');
    }
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function courtCaseFiles()
    {
        return $this->hasMany(CourtCaseFile::class, 'court_case_id');
    }

    public function courtCaseDues()
    {
        return $this->hasMany(CourtCaseDue::class, 'court_case_id', 'id');
    }

    public function getPaidCourtCaseDues()
    {
        $courtCaseDues = [];
        if ($this->courtCaseDues) {
            foreach ($this->courtCaseDues as $courtCaseDue) {
                if ($courtCaseDue->paid == 1) {
                    $courtCaseDues[] = $courtCaseDue;
                }
            }
        }
        return $courtCaseDues;
    }
    public function getCourtCaseDuesUnPaid()
    {
        $courtCaseDues = [];
        if ($this->courtCaseDues) {
            foreach ($this->courtCaseDues as $courtCaseDue) {
                if ($courtCaseDue->paid == 0) {
                    $courtCaseDues[] = $courtCaseDue;
                }
            }
        }
        return $courtCaseDues;
    }

    public function offerEvent(): HasOne
    {
        return $this->hasOne(CourtCaseEvent::class, 'court_case_id', 'id')->whereIn('status', [EventStatusEnum::OFFER->value]);
    }

    public function acceptedEvent(): HasOne
    {
        return $this->hasOne(CourtCaseEvent::class, 'court_case_id', 'id')->whereIn('status', [EventStatusEnum::ACCEPTED->value, EventStatusEnum::FINISHED->value]);
    }

    public function courtCaseLevel(): BelongsTo
    {
        return $this->belongsTo(CourtCaseLevel::class, 'court_case_level_id');
    }

    public function caseSpeciality(): BelongsTo
    {
        return $this->belongsTo(CaseSpecialization::class, 'case_speciality_id');
    }
    public function subCaseSpeciality(): BelongsTo
    {
        return $this->belongsTo(SubCaseSpecializations::class, 'sub_case_speciality_id');
    }
}
