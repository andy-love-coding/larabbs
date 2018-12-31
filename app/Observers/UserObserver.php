<?php

namespace App\Observers;

use App\Models\User;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

// User 模型监控器
class UserObserver
{
    public function saving(User $user)
    {
        // 这样写扩展性更高，只有空的时候才指定默认图像
        if (empty($user->avatar))
        {
            $user->avatar = 'https://iocaffcdn.phphub.org/uploads/images/201710/30/1/TrJS40Ey5k.png';
        }
    }
}