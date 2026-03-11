<?php

namespace App\Actions\Brand;

use App\Models\Brand;

class CreateBrandAction
{
    public function handle(array $data): Brand
    {
        // Slug automatically banana
        $data['slug'] = $data['slug'] ?? str($data['name'])->slug();

        return Brand::create($data);
    }
}
