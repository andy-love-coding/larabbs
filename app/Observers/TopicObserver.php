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
        // clean() 是 HTMLPurifier 插件的方法，'user_topic_body' 是在 config/purifier.php 中配置的
        $topic->body = clean($topic->body, 'user_topic_body'); 
        // make_excerpt() 是在 bootsrap/helper.php 中自定义的辅助函数
        $topic->excerpt = make_excerpt($topic->body);
    }
}