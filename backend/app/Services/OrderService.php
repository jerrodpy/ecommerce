<?php

namespace App\Services;

use App\Http\Requests\Base\Shop\StoreOrderRequest;
use App\Models\CartProductPivot;
use App\Models\Order;
use App\Models\OrderProductPivot;
use App\Models\Product;
use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;
use Illuminate\Support\Arr;

class OrderService
{
    public function __construct(
        private readonly CartRepository $cartRepository,
        private readonly OrderRepository $orderRepository
    ) {
    }

    public function create(StoreOrderRequest $request): Order
    {
        $data = $request->validated();

        $cart = $this->cartRepository->findOrFail(Arr::get($data, 'cart_id'));

        $products = $cart->products->mapWithKeys(fn (Product $product) => [
            $product->id => [
                OrderProductPivot::COLUMN_QUANTITY => $product->pivot->{CartProductPivot::COLUMN_QUANTITY},
                OrderProductPivot::COLUMN_PRICE => $product->price,
            ],
        ]);

        $order = $this->orderRepository->store($data);

        $order->products()->attach($products);
        $order->load('products');

        return $order;
    }
}
