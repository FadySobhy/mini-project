<?php


namespace App\Services\Api\V1;

use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryService
{

    public function create(Request $request) {
        return SubCategory::create($request->validated());
    }

}
