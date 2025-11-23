<?php

namespace App\Http\Resources;

use App\Enums\LawyerStatusEnum;
use App\Models\Lawyer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LawyerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $times = $this->lawyerTimes()->active()->where('lawyer_id', $this->id)->get();
        //            $officeTimeWork = ($this->type == LawyerStatusEnum::OFFICE->value) ?
//         Lawyer::where('office_id', $this->id)->get() : Lawyer::where('office_id', $this->office_id)->get();
        return [
            'id' => $this->id,
            'image' => getFile($this->image),
            'name' => $this->name,
            'email' => $this->email,
            'phone' => (string)$this->phone,
            'national_id' => (string)$this->national_id,
            'lawyer_id' => (string)$this->lawyer_id,
            'type' => (string)$this->type,
            'level' => LevelResource::make($this->level),
            'country' => CountryResource::make($this->country),
            'city' => CityResource::make($this->city),
            'status' => (string)$this->status,
            'lawyer_specialities' => $this->lawyerSpecialities ? LawyerSpecialityResource::collection($this->lawyerSpecialities) : [],
            // 'lawyer_rates' => $this->rates ? RateResource::collection($this->rates) : [],
            'rate_count' => $this->rates ? $this->rates->count() : 0,
            'avg_rate' => $this->rates ? $this->rates->avg('rate') : 0,
            'office_address' => $this->lawyerAbout ? (string)$this->lawyerAbout->office_address : null,
            'lat' => $this->lawyerAbout ? (string)$this->lawyerAbout->lat : null,
            'lng' => $this->lawyerAbout ? (string)$this->lawyerAbout->lng : null,
            'success_case' => $this->lawyerAbout ? (int)$this->lawyerAbout->success_case : null,
            'failed_case' => $this->lawyerAbout ? (int)$this->lawyerAbout->failed_case : null,
            'public_work' => $this->lawyerAbout ? $this->lawyerAbout->public_work : null,
            'about' => $this->lawyerAbout ? (string)$this->lawyerAbout->about : null,
            'consultation_fee' => $this->lawyerAbout ? (int)$this->lawyerAbout->consultation_fee : null,
            'attorney_fee' => $this->lawyerAbout ? (int)$this->lawyerAbout->attorney_fee : null,
            'lawyer_times' => $times ? LawyerTimeResource::collection($times) : [],
//            'office_team_work' => OfficeTeamResource::collection($officeTimeWork),
            'token' => $this->token ?? 'Bearer ' . request()->bearerToken(),
        ];
    }
}
