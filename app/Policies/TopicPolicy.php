<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Topic;

class TopicPolicy extends Policy
{
    public function update(User $user, Topic $topic)
    {
        // return true;                          // 权限放开，任何人都可以访问 update
        // return $topic->user_id == $user->id;  // 只允许自己修改更新自己的话题
        return $user->isAuthorOf($topic);        // 只允许自己修改更新自己的话题
    }

    public function destroy(User $user, Topic $topic)
    {
        // return true;                         // 任何都可以访问 
        // return $topic->user_id == $user->id; // 只允许自己删除自己的话题
        return $user->isAuthorOf($topic);       // 只允许自己删除自己的话题
    }
}
