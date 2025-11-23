<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourtCaseLawyerRateResource extends JsonResource
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
            'from_user' => $this->from_user,
            'to_user' => $this->to_user,
            'from_user_type' => $this->from_user_type,
            'to_user_type'=> $this->to_user_type,
            'rate' => $this->rate,
            'reason' => $this->reason,
            'comment' => $this->comment,
        ];
    }
}
