<?php

namespace App\Http\Resources;

use App\Enums\UserTypeEnum;
use App\Models\Lawyer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostFileResource extends JsonResource
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
            'file'=> getFile($this->file),
            'type'=> $this->type
        ];
    }
}
