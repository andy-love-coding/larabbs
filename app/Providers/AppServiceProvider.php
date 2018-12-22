<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 在 Laravel 中，时间戳 created_at 和 updated_at 作为模型属性被调用时，都会自动转换为 Carbon 对象
        // diffForHumans() 是 Carbon 对象提供的方法，默认情况是英文的，如果要使用中文时间提示，则需要对 Carbon 进行本地化设置
        \Carbon\Carbon::setLocale('zh');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
