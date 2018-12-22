<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

   public function self_can_update(User $currentUser, User $user)
   {
       return $currentUser->id === $user->id; // 当前用户(即登录用户) 等于 要操作的用户 时，才有权限操作
   }
}
