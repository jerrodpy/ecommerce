<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Shop\StoreOrderRequest;
use App\Http\Resource\Admin\OrderResource;
use App\Repositories\OrderRepository;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends BaseController
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly OrderRepository $orderRepository,
    ) {
    }

    public function index(): JsonResponse
    {
        $userId = auth()->id();
        $orders = $this->orderRepository->paginateForUser($userId);

        $this->setData(OrderResource::collection($orders));

        return $this->sendResponse();
    }

    public function store(StoreOrderRequest $request)
    {
        $this->orderService->create($request);

        $this->setMessage('Order created!');

        return $this->sendResponse();
    }
}
