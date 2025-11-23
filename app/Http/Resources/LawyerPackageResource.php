<?php

namespace App\Http\Resources;

use App\Enums\AdConfirmationEnum;
use App\Enums\ExpireEnum;
use App\Enums\StatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LawyerPackageResource extends JsonResource
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
            'status' => StatusEnum::from($this->status)->value,
            'package_id' => $this->package_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'number_of_bumps' => $this->number_of_bumps,
            'number_of_ads' => $this->offerPackage->number_of_ads ?? 0,
            'is_expired' => ExpireEnum::from($this->is_expired)->value,
        ];
    }
}
