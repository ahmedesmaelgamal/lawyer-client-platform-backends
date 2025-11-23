<?php

namespace App\Models;

use App\Enums\UserTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends BaseModel
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_id',
        'user_type',
        'body',
        'count_like',
        'count_dislike'
    ];
    protected $casts = [];

    public function countBlogComments()
    {
        return $this->blogComments->count() +
            $this->blogComments->sum(fn($comment) => $comment->replies->count()) +
            $this->blogComments->sum(fn($comment) => $comment->replies->sum(fn($reply) => $reply->replies->count()));
    }

    public function files()
    {
        return $this->hasMany(BlogFile::class, 'blog_id', 'id');
    }

    public function author()
    {
        return $this->user_type == UserTypeEnum::LAWYER->value ?
            $this->belongsTo(Lawyer::class, 'user_id', 'id') :
            $this->belongsTo(Client::class, 'user_id', 'id');
    }

    public function is_liked()
    {
        return $this->blogReactions()->where('user_id', auth('client_api')->user()->id)
            ->where('user_type', UserTypeEnum::CLIENT->value)
            ->where('reaction', 'like')->exists();
    }

    public function is_disliked()
    {
        return $this->blogReactions()->where('user_id', auth('client_api')->user()->id)
            ->where('user_type', UserTypeEnum::CLIENT->value)
            ->where('reaction', 'dislike')->exists();
    }

    public function lawyer_is_liked()
    {
        $user = auth('lawyer_api')->user();
        if (!$user) return false;

        return $this->blogReactions()->where('user_id', $user->id)
            ->where('user_type', UserTypeEnum::LAWYER->value)
            ->where('reaction', 'like')->exists();
    }

    public function lawyer_is_disliked()
    {
        return $this->blogReactions()->where('user_id', auth('lawyer_api')->user()->id)
            ->where('user_type', UserTypeEnum::LAWYER->value)
            ->where('reaction', 'dislike')->exists();
    }

    public function blogComments()
    {
        return $this->hasMany(BlogComment::class, 'blog_id', 'id');
    }

    public function blogReactions()
    {
        return $this->hasMany(BlogReaction::class, 'blog_id', 'id');
    }

}
