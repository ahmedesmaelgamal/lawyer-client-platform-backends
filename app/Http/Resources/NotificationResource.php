<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class NotificationResource extends JsonResource
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
            'title' => $this->title,
            'body' => $this->body,
            'court_case_id' => $this->court_case_id,
            'seen' => $this->seen,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d h:i a'),
        ];
    }
}
