<?php

namespace App\Observers;

use App\Models\Link;
use Cache;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

// Link 模型监控器
class LinkObserver
{
    // Link 模型有更新保存时，清空 cache_key 对应的缓存
    public function saved(Link $link)
    {
        Cache::forget($link->cache_key);
    }
}