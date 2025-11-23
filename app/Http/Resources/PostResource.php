<?php

namespace App\Http\Resources;

use App\Enums\UserTypeEnum;
use App\Models\Lawyer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $is_liked = false;
        $is_disliked = false;
        if(Auth::guard('client_api')->check()){
            $is_liked = $this->is_liked();
            $is_disliked = $this->is_disliked();
        }else{
            $is_liked = $this->lawyer_is_liked();
            $is_disliked = $this->lawyer_is_disliked();
        }
        return [
            'id' => $this->id,
            'author' => $this->user_type == UserTypeEnum::LAWYER->value ?
                LawyerResource::make($this->author) :
                ClientResource::make($this->author),
            'author_type' => UserTypeEnum::from($this->user_type)->value,
            'body' => $this->body,
            'files' => $this->files->count() ? PostFileResource::collection($this->files) : [],
            'count_like' => (int) $this->count_like,
            'count_dislike' => (int) $this->count_dislike,
            'is_liked' => (bool) $is_liked,
            'is_disliked' => (bool) $is_disliked,
            'count_comments' => (int) $this->countBlogComments(),
            'created_at' => Carbon::parse($this->created_at)->format('d M Y')
        ];
    }
}
