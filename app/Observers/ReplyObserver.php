<?php

namespace App\Observers;

use App\Models\Reply;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    // 新增一条回复时，话题的回复数 view_count 加 1
    public function created(Reply $reply)
    {
        $reply->topic->increment('reply_count', 1);  // 每新增一条回复，view_count 数加 1
    }

    // 回复显示回复内容时用的 {!! !!} 未转义，因此存储之前时需要防 XSS 攻击
    public function creating(Reply $reply)
    {
        // clean() 是 HTMLPurifier 插件的方法，'user_topic_body' 是在 config/purifier.php 中配置的
        $reply->content = clean($reply->content, 'user_topic_body');
    }
}