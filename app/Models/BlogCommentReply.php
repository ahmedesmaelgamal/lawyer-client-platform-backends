<?php

namespace App\Models;

use App\Enums\UserTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Testing\Fluent\Concerns\Has;

class BlogCommentReply extends BaseModel
{
    use SoftDeletes , HasFactory;
    protected $fillable = [
        'user_id',
        'user_type',
        'comment_id',
        'reply_id',
        'reply',
    ];
    protected $casts = [];

    public function comment(){
        return $this->belongsTo(BlogComment::class,'comment_id','id');
    }
    public function reply(){
        return $this->belongsTo(BlogCommentReply::class,'reply_id','id');
    }

    public function replies()
    {
        return $this->hasMany(BlogCommentReply::class,'reply_id','id');
    }

    public function author()
    {
        return $this->user_type == UserTypeEnum::LAWYER->value ?
            $this->belongsTo(Lawyer::class, 'user_id', 'id') :
            $this->belongsTo(Client::class, 'user_id', 'id');
    }
}
