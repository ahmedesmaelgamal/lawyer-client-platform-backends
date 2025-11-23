<?php

namespace App\Http\Resources;

use App\Models\PointTransaction;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PointTransactionResource extends JsonResource
{


    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'points' => $this->points,
            'comment' => $this->comment,
            'created_at'=>$this->created_at->locale('ar')->translatedFormat('d M Y')
        ];
    }
}
