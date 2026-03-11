<?php
// app/Http/Controllers/Api/BrandController.php

namespace App\Http\Controllers\Api;

use App\Actions\Brand\CreateBrandAction;
use App\Actions\Brand\DeleteBrandAction;
use App\Actions\Brand\UpdateBrandAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Resources\BrandResource;
use App\Services\BrandService;

class BrandController extends Controller
{
    public function __construct(
        protected BrandService $brandService
    ) {}

    // ✅ Read — Service use karo
    public function index()
    {
        return BrandResource::collection(
            $this->brandService->getAllBrands()
        );
    }

    public function show(int $id)
    {
        return new BrandResource(
            $this->brandService->getBrand($id)
        );
    }

    // ✅ Write — Action use karo
    public function store(StoreBrandRequest $request, CreateBrandAction $action)
    {
        $brand = $action->handle($request->validated());

        return (new BrandResource($brand))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateBrandRequest $request, int $id, UpdateBrandAction $action)
    {
        $brand = $this->brandService->getBrand($id);
        $updated = $action->handle($brand, $request->validated());

        return new BrandResource($updated);
    }

    public function destroy(int $id, DeleteBrandAction $action)
    {
        $brand = $this->brandService->getBrand($id);
        $action->handle($brand);

        return response()->json(['message' => 'Brand deleted!'], 200);
    }
}
