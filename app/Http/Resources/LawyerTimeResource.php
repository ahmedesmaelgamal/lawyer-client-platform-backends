<?php

namespace App\Http\Resources;

use App\Enums\StatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LawyerTimeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => (int)$this->id,
            'day' => (string)$this->day,
            'from' => (string)\Carbon\Carbon::parse($this->from)->format('H:i'),
            'to' => (string)\Carbon\Carbon::parse($this->to)->format('H:i'),
            'status' => $this->status ?? null
        ];
    }
}
