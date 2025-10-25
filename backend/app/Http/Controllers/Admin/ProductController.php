<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\ProductRequest;
use App\Http\Requests\Base\ListWithPaginationRequest;
use App\Http\Resource\Admin\ProductResource;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ProductController extends BaseController
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly ProductService $productService,
    ) {
    }

    public function index(ListWithPaginationRequest $request): JsonResponse
    {
        $this->setData($this->productRepository->paginate($request->validated()));

        return $this->sendResponse();
    }

    public function store(ProductRequest $request): JsonResponse
    {
        $product = $this->productService->create($request);

        $this->setData(ProductResource::make($product));
        $this->setMessage('Product successfully created');
        $this->setStatusCode(Response::HTTP_CREATED);

        return $this->sendResponse();
    }

    /**
     * @throws \Throwable
     */
    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        $product = $this->productService->update($request, $product);

        $this->setData(ProductResource::make($product));

        return $this->sendResponse();
    }

    public function destroy(Product $product): JsonResponse
    {
        $result = $this->productService->delete($product);

        $this->setMessage($result ? 'Product successfully delete' : 'Error while deleting');
        $this->setStatusCode($result ? Response::HTTP_OK : Response::HTTP_INTERNAL_SERVER_ERROR);

        return $this->sendResponse();
    }
}
