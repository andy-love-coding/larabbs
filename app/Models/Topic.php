<?php

namespace App\Models;

class Topic extends Model
{
    // 允许用户修改的字段
    protected $fillable = ['title', 'body', 'category_id', 'excerpt', 'slug'];

    // 话题(帖子)关联回复：一个话题，拥有多个回复
    public function replies()
    {
        // return $this->hasMany(Reply::class);
        return $this->hasMany(Reply::class, 'topic_id', 'id');
    }

    // 话题(帖子)关联分类：一个话题，属于一个分类
    public function category()
    {
        // return $this->belongsTo(Category::class);
        return $this->belongsTo(Category::class,'category_id','id');
    }

    // 话题(帖子)关联用户：一个话题，拥有一个用户
    public function user()
    {
        // return $this->belongsTo(User::class);
        return $this->belongsTo(User::class,'user_id','id');
    }

    // scope 是本地作用域，$query 是一个查询构建器，在调用本地作用域的方法时，不用加 scope,
    // 在控制器中，先给控制器方法传递一个 Topic $topic 实例参数, 再用实例调用方法，如：$topic->withOrder($order); 【记得】用实例调用时，不用传递查询构建器参数
    public function scopeWithOrder($query, $order)
    {
        // 不同的排序，使用不同的数据读取逻辑
        switch($order) {
            case 'recent':
                $query->recent();
                break;
            default:
                $query->recentReplied();
                break;
        }
        // 预加载防止 N+1 问题
        return $query->with('user', 'category');        
    }

    public function scopeRecentReplied($query)
    {
        // 当话题有新回复时，我们将编写逻辑来更新话题模型的 reply_count 属性，
        // 此时，会自动触发框架对数据模型 updated_at 时间戳的更新
        return $query->orderBy('updated_at', 'desc');
    }

    // 在控制器中，Topic模型数据集合，也可以调用此排序方法，
    // 如：$uesr->topics()->recent()->paginate(5); 意思是：得到某用户的话题，按创建时间最近的排序，每页 5 条。
    public function scopeRecent($query)
    {
        // 按照创建时间排序
        return $query->orderBy('created_at', 'desc');
    }

    // 生成对话题 SEO 友好的链接
    public function link($params = [])
    {
        return route('topics.show', array_merge([$this->id, $this->slug], $params));
    }
}
