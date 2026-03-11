<?php

// app/Observers/PostObserver.php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PostObserver
{
    public function created(Post $post): void
    {
        Log::info("✅ Post created", ['id' => $post->id]);
        $this->clearPostsCache();
    }

    public function updated(Post $post): void
    {
        Log::info("📝 Post updated", ['id' => $post->id]);
        $this->clearPostsCache();
        Cache::forget("post_{$post->id}");
    }

    public function deleted(Post $post): void
    {
        Log::warning("🗑️ Post deleted", ['id' => $post->id]);
        $this->clearPostsCache();
        Cache::forget("post_{$post->id}");
    }

    private function clearPostsCache()
    {
        $page = 1;
        while (Cache::has("all_posts_page_{$page}")) {
            Cache::forget("all_posts_page_{$page}");
            $page++;
        }
    }
}
