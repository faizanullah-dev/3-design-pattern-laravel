<?php

namespace App\Services;

use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Support\Facades\Cache; // ✅ Sirf yeh

class PostService
{
    public function __construct(
        protected PostRepositoryInterface $postRepository
    ) {}

    public function getAllPosts()
    {
        $page = request()->get('page', 1);
        return Cache::remember("all_posts_page_{$page}", 3600, function () {
            return $this->postRepository->getAll();
        });
    }

    public function getPost(int $id)
    {
        return Cache::remember("post_{$id}", 3600, function () use ($id) {
            return $this->postRepository->findById($id);
        });
    }

    public function createPost(array $data)
    {
        $data['status'] = $data['status'] ?? 'draft';
        return $this->postRepository->create($data);
    }

    public function updatePost(int $id, array $data)
    {
        return $this->postRepository->update($id, $data);
    }

    public function deletePost(int $id)
    {
        return $this->postRepository->delete($id);
    }
}
