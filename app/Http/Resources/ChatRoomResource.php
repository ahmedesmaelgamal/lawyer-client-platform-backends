<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ChatRoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (Auth::guard('client_api')->check()) {
            return [
                'id' => $this->id,
                'uuid' => $this->uuid,
                'user' => new CommentAuthorResource($this->lawyer()->select('id', 'name', 'image')->first()),
            ];
        } else {
            return [
                'id' => $this->id,
                'uuid' => $this->uuid,
                'user' => new CommentAuthorResource($this->client()->select('id', 'name', 'image')->first()),
            ];
        }
    }
}
