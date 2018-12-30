<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return false;
        return true; // 所有权限都通过
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // unique:table,column,except,idColumn 意思为：
            // 在 table 数据表里检查 column ，除了 idColumn 为 except 的数据。idColumn 的默认值是 “id”。
            // 编辑自己的名称时，排除是否唯一时，需要把自己排除在对比范围外；新建时就不必排除了。
            'name' => 'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,' . Auth::id(),
            'email' => 'required|email',
            'introduction' => 'max:80',
            'avatar' => 'mimes:jpeg,bmp,png,jif|dimensions:min_width=200,min_height=200',
        ];
    }

    public function messages()
    {
        return [
            'avatar.mimes' => '头像必须是 jpeg, bmp, png, gif 格式的图片',
            'avatar.dimensions' => '图片清晰度不够，宽和高需要 200px 以上',
            'name.unique' => '用户名已经被占用，请重新填写',
            'name.regex' => '用户名只支持英文、数字、衡航和下划线。',
            'name.between' => '用户名必须介于 3 - 25 个字符之间。',
            'name.required' => '用户名不能为空。',
        ];
    }
}
