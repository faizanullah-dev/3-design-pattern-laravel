<?php
// app/Observers/CategoryObserver.php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CategoryObserver
{
    public function created(Category $category): void
    {
        Log::info("✅ Category created", ['id' => $category->id]);
        $this->clearCache();
    }

    public function updated(Category $category): void
    {
        Log::info("📝 Category updated", ['id' => $category->id]);
        $this->clearCache();
        Cache::forget("category_{$category->id}");
    }

    public function deleted(Category $category): void
    {
        Log::warning("🗑️ Category deleted", ['id' => $category->id]);
        $this->clearCache();
        Cache::forget("category_{$category->id}");
    }

    private function clearCache(): void
    {
        $page = 1;
        while (Cache::has("all_categories_page_{$page}")) {
            Cache::forget("all_categories_page_{$page}");
            $page++;
        }
    }
}
