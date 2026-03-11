<?php

namespace App\Http\Controllers\API;

use App\DTOs\Category\CreateCategoryDTO;
use App\DTOs\Category\UpdateCategoryDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService
    ) {}

    public function index()
    {
        return CategoryResource::collection(
            $this->categoryService->getAllCategories()
        );
    }

    public function show(int $id)
    {
        return new CategoryResource(
            $this->categoryService->getCategory($id)
        );
    }

    public function store(StoreCategoryRequest $request)
    {
        // ✅ Request → DTO → Service
        $dto = CreateCategoryDTO::fromRequest($request->validated());
        $category = $this->categoryService->createCategory($dto);

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateCategoryRequest $request, int $id)
    {
        // ✅ Request → DTO → Service
        $dto = UpdateCategoryDTO::fromRequest($request->validated());
        $category = $this->categoryService->updateCategory($id, $dto);

        return new CategoryResource($category);
    }

    public function destroy(int $id)
    {
        $this->categoryService->deleteCategory($id);
        return response()->json(['message' => 'Category deleted!'], 200);
    }
}
