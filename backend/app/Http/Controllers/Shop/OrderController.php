<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Shop\StoreOrderRequest;
use App\Services\OrderService;

class OrderController extends BaseController
{
    public function __construct(private readonly OrderService $orderService)
    {
    }

    public function store(StoreOrderRequest $request)
    {
        $this->orderService->create($request);

        $this->setMessage('Order created!');

        return $this->sendResponse();
    }
}
