<?php

namespace App\Actions\Brand;

use App\Models\Brand;

class DeleteBrandAction
{
    public function handle(Brand $brand): bool
    {
        return $brand->delete();
    }
}
