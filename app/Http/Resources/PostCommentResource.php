<?php

namespace App\Http\Resources;

use App\Enums\UserTypeEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $author = $this->author()->select('id', 'name', 'image')->first();
        return [
            'id' => $this->id,
            'author' => CommentAuthorResource::make($this->author()->select('id', 'name', 'image')->first()),
            'comment' => $this->comment,
            'user_type' => UserTypeEnum::from($this->user_type)->value,
            'created_at' => Carbon::parse($this->created_at)->format('d M Y h:i A'),
//            'replies'=> PostCommentReplyResource::collection($this->replies()->where('comment_id', $this->id)->where('reply_id', null)->get())
        ];
    }
}
