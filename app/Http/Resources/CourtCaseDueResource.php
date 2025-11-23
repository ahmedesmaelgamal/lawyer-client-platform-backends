<?php

namespace App\Http\Resources;

use App\Enums\DuePaidEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourtCaseDueResource extends JsonResource
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
            'title' => $this->title,
            'date' => $this->date,
            'price' => $this->price,
            'paid'  => $this->paid,
            'paid_name' => DuePaidEnum::tryFrom($this->paid)->lang(),
        ];
    }
}
