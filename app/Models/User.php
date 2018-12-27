<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

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
}
