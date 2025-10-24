<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Shop\ListProductRequest;
use App\Repositories\ProductRepository;

class ProductController extends BaseController
{
    public function __construct(
        private readonly ProductRepository $productRepository
    ) {
    }

    public function index(ListProductRequest $request)
    {
        $this->setData($this->productRepository->paginate($request->validated()));

        return $this->sendResponse();
    }
}
