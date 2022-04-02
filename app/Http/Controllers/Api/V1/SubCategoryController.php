<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\SubCategoryRequest;
use App\Services\Api\V1\SubCategoryService;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    protected $subCategoryService;

    public function __construct(SubCategoryService $subCategoryService)
    {
        $this->subCategoryService = $subCategoryService;
    }

    public function create(SubCategoryRequest $subCategoryRequest)
    {
        $subCategory = $this->subCategoryService->create($subCategoryRequest);

        return response()->success($subCategory);
    }
}
