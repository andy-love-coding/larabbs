<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Handlers\ImageUploadHandler;

class UsersController extends Controller
{
    // 展示个人页面: 变量名 $user 会匹配路由片段中的 {user}，这样，Laravel 会自动注入与请求 URI 中传入的 ID 对应的用户模型实例。
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    // 展示编辑个人编辑页面
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // 更新用户资料
    public function update(UserRequest $request, ImageUploadHandler $uploader , User $user)
    {   
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
