<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfficeTeamResource extends JsonResource
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
            'image' => getFile($this->image),
            'name' => $this->name,
            'type' => (string) $this->type,
            'level' => LevelResource::make($this->level),
            'office_id'=>$this->office_id
        ];
    }
}
