<?php

namespace App\Http\Resources;

use App\Enums\UserTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostCommentReplyResource extends JsonResource
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
            'author' => CommentAuthorResource::make($this->author()->select('id', 'name', 'image')->first()),
            'reply' => $this->reply,
            'user_type' => UserTypeEnum::from($this->user_type)->value,
            'created_at' => $this->created_at,
//            'replies'=> $this->replies ? PostCommentReplyResource::collection($this->replies) : [],
        ];
    }
}
