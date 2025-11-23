<?php

namespace App\Http\Resources;

use App\Enums\CourtCaseStatusEnum;
use App\Enums\SosRequestStatusEnum;
use App\Models\Speciality;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SosRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "problem" => $this->problem,
            "phone" => $this->phone,
            "address" => $this->address,
            "lat" => $this->lat,
            "long" => $this->long,
//            dd($this->voice),
            'voice' => $this->voice ?asset($this->voice) : null,
            "status" => SosRequestStatusEnum::from($this->status)->value,
            "lawyer" => LawyerResource::make($this->lawyer),
            "created_at" => $this->created_at,
        ];
    }
}
