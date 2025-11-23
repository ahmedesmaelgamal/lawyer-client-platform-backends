<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogReaction extends BaseModel
{
    use SoftDeletes , HasFactory;
    protected $fillable = [
        'user_id',
        'user_type',
        'blog_id',
        'reaction'
    ];
    protected $casts = [];


    public function client()
    {
        return $this->belongsTo(Client::class, 'user_id', 'id');
    }
    public function blog(){
       return $this->belongsTo(Blog::class,'blog_id','id');
    }
}
