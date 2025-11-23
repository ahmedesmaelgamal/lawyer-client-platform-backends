<?php

namespace App\Http\Resources;

use App\Enums\AdConfirmationEnum;
use App\Enums\StatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LawyerAdResource extends JsonResource
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
            // 'lawyer_id' => LawyerResource::collection($this->whenLoaded('lawyer')),
            'status' => StatusEnum::from($this->status)->value,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'link' => $this->link,
            'image' => getFile($this->image),
            'ad_confirmation' => AdConfirmationEnum::from($this->ad_confirmation)->value,
        ];
    }
}
