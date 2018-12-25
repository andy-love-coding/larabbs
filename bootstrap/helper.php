<?php

function route_class()
{
    // `Route::currentRouteName()` 会获取当前路由的名称，即 ->name('路由名称') 中的名称，而不是获取路由路径
    return str_replace('.', '-', Route::currentRouteName());
}

function make_excerpt($value, $length = 200)
{
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
    return str_limit($excerpt, $length);
}