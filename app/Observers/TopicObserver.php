<?php

namespace App\Observers;

use App\Models\Topic;
// use App\Handlers\SlugTranslateHandler;
use App\Jobs\TranslateSlug;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

// Topic 模型监控器
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

    // 数据入库前：进行过滤(防止xss攻击，过滤掉危险代码)、属性赋值（给 except、slut 等字段赋值）
    public function saving(Topic $topic)
    {
        // 虽然前端编辑器已经过滤了，但是后端 HTMLPurifier 服务器端过滤依然是必须的，因为前端过滤可以用 postman 绕过。
        // 总结：凡是用户提交的数据在显示时不进行 html 转义的，都需要在服务端进行 XSS 过滤
        // clean() 是 HTMLPurifier 插件的方法，'user_topic_body' 是在 config/purifier.php 中配置的
        $topic->body = clean($topic->body, 'user_topic_body'); 
        // $topic->title = clean($topic->title, 'user_topic_body'); // title 不用过滤，{{}}输出是会对html进行转义
        
        // 生成话题摘录：make_excerpt() 是在 bootsrap/helper.php 中自定义的辅助函数
        $topic->excerpt = make_excerpt($topic->body);

        // 如 slug 字段无内容，即使用翻译器对 title 进行翻译

        // 不使用队列，直接同步进行翻译，对系统性能稳定性影响较大
        // if ( ! $topic->slug) {
        //     $topic->slug = app(SlugTranslateHandler::class)->translate($topic->title);
        // }
    }

    // 使用队列，队列任务需要序列化模型的ID，所以要在模型存入数据后，模型才会有id
    public function saved(Topic $topic)
    {
        // 使用队列：推送任务到队列。有时间慢慢来做，不慌不忙。
        if ( ! $topic->slug) {            
            dispatch(new TranslateSlug($topic));  // 任务分发
        }
    }

    // 监听话题被删除时：连带删除该话题下的所有回复
    // 【需要注意的是】：在模型监听器中，数据库操作需要避免再次触发 Eloquent 事件，所以这里我们使用了 DB 类进行操作。
    public function deleted(Topic $topic)
    {
        \DB::table('replies')->where('topic_id', $topic->id)->delete();
    }
}