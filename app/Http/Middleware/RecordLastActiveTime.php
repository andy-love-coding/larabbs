<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class RecordLastActiveTime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 如果是登录用户的话
        if (Auth::check()) {
            // 记录最后登录的事件
            Auth::user()->recordLastActivedAt();
        }
        return $next($request);
    }
}
