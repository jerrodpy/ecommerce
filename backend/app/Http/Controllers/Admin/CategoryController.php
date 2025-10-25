<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\CategoryRequest;
use App\Http\Requests\Base\ListWithPaginationRequest;
use App\Http\Resource\CategoryResource;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CategoryController extends BaseController
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository
    ) {
    }

    public function index(ListWithPaginationRequest $request): JsonResponse
    {
        $this->setData($this->categoryRepository->paginate($request->validated()));

        return $this->sendResponse();
    }

    public function store(CategoryRequest $request): JsonResponse
    {
        $category = $this->categoryRepository->store($request->validated());
        $this->setData(CategoryResource::make($category));
        $this->setMessage('Category successfully created');
        $this->setStatusCode(Response::HTTP_CREATED);

        return $this->sendResponse();
    }

    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        $category->update($request->validated());

        $this->setData(CategoryResource::make($category));
        $this->setMessage('Category successfully updated');

        return $this->sendResponse();
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        $this->setMessage('Category successfully delete');
        $this->setStatusCode(Response::HTTP_OK);
//        $this->setStatusCode(Response::HTTP_NO_CONTENT);

        return $this->sendResponse();
    }
}
