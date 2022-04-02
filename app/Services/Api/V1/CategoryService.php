<?php


namespace App\Services\Api\V1;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryService
{

    public function create(Request $request) {
        return Category::create($request->validated());
    }

}
