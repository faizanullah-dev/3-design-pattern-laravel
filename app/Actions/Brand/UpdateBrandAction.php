<?php

namespace App\Actions\Brand;

use App\Models\Brand;

class UpdateBrandAction
{
    public function handle(Brand $brand, array $data): Brand
    {
        if (isset($data['name']) && !isset($data['slug'])) {
            $data['slug'] = str($data['name'])->slug();
        }

        $brand->update($data);
        return $brand->fresh();
    }
}
