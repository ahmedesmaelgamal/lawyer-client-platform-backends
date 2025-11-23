<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'name' => $this->name,
            'image' => getFile($this->image),
            'email' => $this->email,
            'phone' => (string) $this->phone,
            'national_id'=> (string) $this->national_id,
            'points' => (string) $this->lawyer_id,
            'city' => new CityResource($this->city),
            'status' => (string) $this->status,
            'token' => $this->token ?? 'Bearer '.request()->bearerToken(),
            'rates_count' => $this->rates->count(),
            'rates' => $this->rates->avg('rate'),
        ];
    }
}
