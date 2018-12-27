<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Reply;

// 1.0 定义通知类
class TopicReplied extends Notification
{
    use Queueable;

    public $reply;
    
    // 2.0 在模型监视器中，通知的调用时：需实例化通知类，并注入一个模型对象，因此在构造函数中需要一个模型对象形参
    public function __construct(Reply $reply)
    {
        // 注入回复实体，方便 toDatabase 方法中的使用
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // return ['mail'];
        
        // 开启通知的频道
        return ['database'];
    }    

    public function toDatabase($notifiable)
    {
        $topic = $this->reply->topic;
        $link =  $topic->link(['#reply' . $this->reply->id]);

        // 这个返回的数组将被转成 JSON 格式并存储到通知数据表的 data 字段中。
        return [
            'reply_id' => $this->reply->id,
            'reply_content' => $this->reply->content,
            'user_id' => $this->reply->user->id,
            'user_name' => $this->reply->user->name,
            'user_avatar' => $this->reply->user->avatar,
            'topic_link' => $link,
            'topic_id' => $topic->id,
            'topic_title' => $topic->title,
        ];
    }
}
