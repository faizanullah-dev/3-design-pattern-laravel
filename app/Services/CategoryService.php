<?php

namespace App\Services;

use App\DTOs\Category\CreateCategoryDTO;
use App\DTOs\Category\UpdateCategoryDTO;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class CategoryService
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    public function getAllCategories()
    {
        $page = request()->get('page', 1);
        return Cache::remember("all_categories_page_{$page}", 3600, function () {
            return $this->categoryRepository->getAll();
        });
    }

    public function getCategory(int $id)
    {
        return Cache::remember("category_{$id}", 3600, function () use ($id) {
            return $this->categoryRepository->findById($id);
        });
    }

    public function createCategory(CreateCategoryDTO $dto): mixed
    {
        return $this->categoryRepository->create([
            'name'        => $dto->name,
            'slug'        => $dto->slug,
            'description' => $dto->description,
        ]);
    }

    public function updateCategory(int $id, UpdateCategoryDTO $dto): mixed
    {
        return $this->categoryRepository->update($id, $dto->toArray());
    }

    public function deleteCategory(int $id): mixed
    {
        return $this->categoryRepository->delete($id);
    }
}
