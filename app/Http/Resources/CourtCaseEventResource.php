<?php

namespace App\Http\Resources;

use App\Enums\CourtCaseStatusEnum;
use App\Enums\EventStatusEnum;
use App\Models\CourtCaseEvent;
use App\Models\Speciality;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CourtCaseEventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {


        return [
            'id' => $this->id,
            'lawyer_id'=>  $this->lawyer ? $this->lawyer->id : null,
            'lawyer_name' => $this->lawyer?$this->lawyer->name:"",
            'lawyer_level'=> $this->lawyer?$this->lawyer->level()->select('title')->first()->title:"",
            'lawyer_image'=> $this->lawyer?getFile($this->lawyer->image):"",
            'rate_count' =>$this->lawyer? ($this->lawyer->rates ? $this->lawyer->rates->count() : 0):"",
            'avg_rate' => $this->lawyer?($this->lawyer->rates ? $this->lawyer->rates->avg('rate') : 0):"",
            'status' => EventStatusEnum::from($this->status)->value,
            'price' => (int) $this->price,
            'refuse_reason'=> RefuseReasonResource::make($this->refuseReason),
            'refuse_note'=> $this->refuse_note,
            'dues'=> CourtCaseDueResource::collection($this->dues),
            'created_at' => $this->created_at,
        ];
    }
}
