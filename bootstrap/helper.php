<?php

// 获取当前路由名称，并把 "." 替换成 "_" 
function route_class()
{
    // `Route::currentRouteName()` 会获取当前路由的名称，即 ->name('路由名称') 中的名称，而不是获取路由路径
    return str_replace('.', '-', Route::currentRouteName());
}

// 生成摘要
function make_excerpt($value, $length = 200)
{
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
    return str_limit($excerpt, $length);
} 

// 获取带前缀的模型链接，如：larabbs.test/admin/users/13
function model_admin_link($title, $model)
{
    return model_link($title, $model, 'admin');
}

// 获取模型的链接，如: larabbs.test/users/13
function model_link($title, $model, $prefix = '')
{
    // 获取数据模型的复数蛇形命名
    $model_name = model_plural_name($model);

    // 初始化前缀
    $prefix = $prefix ? "/$prefix/" : '/';

    // 使用站点 URL 拼接全量 URL
    $url = config('app.url') . $prefix . $model_name . '/' . $model->id;

    // 拼接 HTML A 标签，并返回
    return '<a href="' . $url . '" target="_blank">' . $title . '</a>';
}

// 根据模型 来获取其 复数蛇形命名
function model_plural_name($model)
{
    // 从实体中获取完整类名，例如：App\Models\User
    $full_class_name = get_class($model);

    // 获取基础类名，例如：传参 `App\Models\User` 会得到 `User`
    $class_name = class_basename($full_class_name);

    // 蛇形命名，例如：传参 `User`  会得到 `user`, `FooBar` 会得到 `foo_bar`
    $snake_case_name = snake_case($class_name);

    // 获取子串的复数形式，例如：传参 `user` 会得到 `users`
    return str_plural($snake_case_name);
}