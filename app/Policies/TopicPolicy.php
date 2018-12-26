<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Topic;

class TopicPolicy extends Policy
{
    public function update(User $user, Topic $topic)
    {
        return $topic->user_id == $user->id;  // 只允许自己修改更新自己的话题
        // return true; // 权限放开
    }

    public function destroy(User $user, Topic $topic)
    {
        return true;
    }
}
