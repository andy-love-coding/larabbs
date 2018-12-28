<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Spatie\Permission\Traits\HasRoles; 

class User extends Authenticatable
{
    use HasRoles; // laravel-permission 提供的 Trait —— HasRoles，能让我们获取到扩展包提供的所有：权限和角色的操作方法。

    // use Notifiable; // 原来只是简单使用 Notifiable ，现在需要在通知的时候，给用户表中的 notification_count 加 1
    use Notifiable {
        notify as protected laravelNotify;  // 使用 Notifiable trait ，并把它的 nofify() 方作为 User 类的一个别名方法
    }
    public function notify($instance)  // 在 User 类中，封装一个自定义的 nofity()，其实是对 Notifiable 中 nofity() 的重写
    {
        // 如果要通知的人是当前用户，就不必通知了！
        if ($this->id == Auth::id()) {
            return;
        }
        $this->increment('notification_count');

        // 以上是自定义 nofity() 方法的逻辑代码；以下是调用真正的 notify() 方法（ 即 Notifiable trait 中的方法 ）

        $this->laravelNotify($instance);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','introduction','avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     * 在将模型集合数据 转换 成数组或json时，这些字段会隐藏，不会出现在数组或json中
     * 如果需要将 模型集合数据 转换 成数组或json时，数组或josn中含有隐藏字段，
     * 那么可以用 $users->makeVisible(['password','remember_token'])->toArray();
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // 用户关联话题：一个用户 对应 多个话题
    public function topics()
    {
        // return $this->hasMany(Topic::class);
        return $this->hasMany(Topic::class,'user_id','id');
    }

    // 用户关联话题：一个用户 对应 多个话题
    public function replies()
    {
        // return $this->hasMany(Reply::class);
        return $this->hasMany(Reply::class, 'user_id', 'id');
    }

    // 用户授权：只有当前登录用户id 与 授权对象用户id 相等时才有权限
    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;  // $this->id 表示当前登录用户id，$model->user_id 表示授权对象的用户id
    }

    // 用户消息已读，需要做两个动作：更新用户表消息数量、更新消息通知表已读(read_at)时间
    public function markAsRead() // 这是我们自定义的 markAsRead() 方法
    {
        // 1.0 消息已读时：将用户表中，用户消息的数量置零
        $this->notification_count = 0;
        $this->save();

        // 2.0 unreadNotifications 是 Notifiable trait 的方法，获取未读消息集合，这个集合提供一个 markAsRead() 方法，
        // 它可以批量的把未读消息集合标记为已读（即在 notifications 数据表中更新 read_at 字段，值为当前时间 ）
        $this->unreadNotifications->markAsRead();  // 
    }
}
