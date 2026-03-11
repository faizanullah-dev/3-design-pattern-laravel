<?php

namespace App\Services;

use App\Repositories\Interfaces\BrandRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class BrandService
{
    public function __construct(
        protected BrandRepositoryInterface $brandRepository
    ) {}
 
    public function getAllBrands()
    {
        $page = request()->get('page', 1);
        return Cache::remember("all_brands_page_{$page}", 3600, function () {
            return $this->brandRepository->getAll();
        });
    }

    public function getBrand(int $id)
    {
        return Cache::remember("brand_{$id}", 3600, function () use ($id) {
            return $this->brandRepository->findById($id);
        });
    }
}
