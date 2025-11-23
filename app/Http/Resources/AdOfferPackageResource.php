<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdOfferPackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'title' => $this->title,
            'number_of_days' => (int) $this->number_of_days,
            'number_of_ads' => (int) $this->number_of_ads,
            'price' => $this->price,
            'discount' => (int) $this->discount,
            'status' => $this->status,
        ];
    }
}
