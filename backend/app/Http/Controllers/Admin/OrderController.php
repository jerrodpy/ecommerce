<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\OrderRequest;
use App\Http\Requests\Base\ListWithPaginationRequest;
use App\Http\Resource\Admin\OrderResource;
use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Http\JsonResponse;

class OrderController extends BaseController
{
    public function __construct(
        private readonly OrderRepository $orderRepository
    ) {
    }

    public function index(ListWithPaginationRequest $request): JsonResponse
    {
        $this->setData($this->orderRepository->paginate($request->validated()));

        return $this->sendResponse();
    }

    public function update(OrderRequest $request, Order $order): JsonResponse
    {
        $order->update($request->validated());

        $this->setData(OrderResource::make($order));
        $this->setMessage('Order successfully updated');

        return $this->sendResponse();
    }
}
