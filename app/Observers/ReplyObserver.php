<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\TopicReplied;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    // 新增一条回复时，话题的回复数 reply_count 加 1；并触发通知，通知作者
    public function created(Reply $reply)
    {
        $topic = $reply->topic;

        $topic->increment('reply_count', 1);  // 每新增一条回复，view_count 数加 1

        // 默认的 User 模型中使用了 trait —— Notifiable，它包含着一个可以用来发通知的方法 notify()
        // 通知话题的作者，话题被回复了；通知的调用：实例化通知类，并注入一个模型对象
        // 通知时，需要自增用户表的 notification_count 字段，因此需要在 User 模型中重写 notify() 方法。
        $topic->user->notify(new TopicReplied($reply)); 
    }

    // 删除一条回复时，话题的回复数 reply_count 减 1；
    public function deleted(Reply $reply)
    {
        $reply->topic->decrement('reply_count', 1);
    }

    // 回复显示回复内容时用的 {!! !!} 未转义，因此存储之前时需要防 XSS 攻击
    public function creating(Reply $reply)
    {
        // clean() 是 HTMLPurifier 插件的方法，'user_topic_body' 是在 config/purifier.php 中配置的
        $reply->content = clean($reply->content, 'user_topic_body');
    }

    
}