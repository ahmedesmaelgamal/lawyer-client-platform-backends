<?php

namespace App\Models;

use App\Enums\UserTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogComment extends BaseModel
{
    use SoftDeletes , HasFactory;
    protected $fillable = [
        'blog_id',
        'user_id',
        'user_type',
        'comment'
    ];
    protected $casts = [];

    public function blog()
    {
        return $this->belongsTo(Blog::class,'blog_id','id');
    }

    public function replies()
    {
        return $this->hasMany(BlogCommentReply::class,'comment_id','id');
    }

    public function author()
    {
        return $this->user_type == UserTypeEnum::LAWYER->value ?
            $this->belongsTo(Lawyer::class, 'user_id', 'id') :
            $this->belongsTo(Client::class, 'user_id', 'id');
    }
}
