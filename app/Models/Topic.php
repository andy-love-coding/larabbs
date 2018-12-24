<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = ['title', 'body', 'user_id', 'category_id', 'reply_count', 'view_count', 'last_reply_user_id', 'order', 'excerpt', 'slug'];

    // 话题(帖子)关联分类：一个话题，属于一个分类
    public function category()
    {
        // return $this->belongsTo(Category::class);
        return $this->belongsTo(Category::class,'category_id','id');
    }

    // 话题(帖子)关联分类：一个话题，拥有一个用户
    public function user()
    {
        // return $this->belongsTo(User::class);
        return $this->belongsTo(User::class,'user_id','id');
    }
}
