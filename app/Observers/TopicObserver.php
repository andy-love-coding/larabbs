<?php

namespace App\Observers;

use App\Models\Topic;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function creating(Topic $topic)
    {
        //
    }

    public function updating(Topic $topic)
    {
        //
    }

    // 数据入库前：进行过滤(防止xss攻击，过滤掉危险代码)、属性赋值
    public function saving(Topic $topic)
    {
        // 虽然前端编辑器已经过滤了，但是后端 HTMLPurifier 服务器端过滤依然是必须的，因为前端过滤可以用 postman 绕过。
        // 总结：凡是用户提交的数据在显示时不进行 html 转义的，都需要在服务端进行 XSS 过滤
        // clean() 是 HTMLPurifier 插件的方法，'user_topic_body' 是在 config/purifier.php 中配置的
        $topic->body = clean($topic->body, 'user_topic_body'); 
        // $topic->title = clean($topic->title, 'user_topic_body'); // title 不用过滤，{{}}输出是会对html进行转义
        
        // make_excerpt() 是在 bootsrap/helper.php 中自定义的辅助函数
        $topic->excerpt = make_excerpt($topic->body);
    }
}