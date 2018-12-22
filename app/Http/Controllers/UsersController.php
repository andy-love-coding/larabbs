<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Handlers\ImageUploadHandler;

class UsersController extends Controller
{
    public function __construct()
    {
        // 用 auth 中间件来过来登录用户的动作
        $this->middleware('auth',[
            'except' => ['show']  // 除了这几个动作(方法)，其余动作(方法)都需要登录才能访问
        ]);
    }

    // 展示个人页面: 变量名 $user 会匹配路由片段中的 {user}，这样，Laravel 会自动注入与请求 URI 中传入的 ID 对应的用户模型实例。
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    // 展示编辑个人编辑页面
    public function edit(User $user)
    {
        $this->authorize('self_can_update',$user); // 对需要操作的对象(用户$user)进行权限验证：只有自己能编辑自己的数据
        return view('users.edit', compact('user'));
    }

    // 更新用户资料
    public function update(UserRequest $request, ImageUploadHandler $uploader , User $user)
    {   
        $this->authorize('self_can_update',$user); // 对需要操作的对象(用户$user)进行权限验证：只有自己能更新自己的数据
        $data = $request->all();
        // dd($data); // $data['avatar'] 的值为 UploadedFile 对象
        // dd($request->avatar); // $request->avatar 的值也为 UploadedFile 对象

        // 若上传图片成功，更新 $data['avatar'] 的值为图片地址
        if ($request->avatar) {
            $result = $uploader->save($request->avatar, 'avatars', $user->id, 400);
            if ($result) {
                $data['avatar'] = $result['path'];
            }
        }

        $user->update($data);
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
    }
}
