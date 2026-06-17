<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Shop\ListProductRequest;
use App\Http\Resource\Shop\ProductCollectionResource;
use App\Models\Product;
use App\Repositories\ProductRepository;

class ProductController extends BaseController
{
    public function __construct(
        private readonly ProductRepository $productRepository
    ) {
    }

    public function index(ListProductRequest $request)
    {
        $this->setData($this->productRepository->paginateWithCategories($request->validated()));

        return $this->sendResponse();
    }

    public function show(Product $product)
    {
        $product->load(Product::RELATION_CATEGORIES);

        $this->setData(ProductCollectionResource::make($product));

        return $this->sendResponse();
    }
}
