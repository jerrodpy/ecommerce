<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Base\ListWithPaginationRequest;
use App\Repositories\CategoryRepository;

class CategoryController extends BaseController
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository
    ) {
    }

    public function index(ListWithPaginationRequest $request)
    {
        return $this->sendJsonResponse($this->categoryRepository->paginate($request->validated()));
    }
}
