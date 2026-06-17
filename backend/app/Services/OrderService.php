<?php

namespace App\Services;

use App\Http\Requests\Shop\StoreOrderRequest;
use App\Models\CartProductPivot;
use App\Models\Order;
use App\Models\OrderProductPivot;
use App\Models\Product;
use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        private readonly CartRepository $cartRepository,
        private readonly OrderRepository $orderRepository
    ) {
    }

    /**
     * @throws \Throwable
     */
    public function create(StoreOrderRequest $request): Order
    {
        $data = $request->validated();
        $user = $request->user();

        if ($user) {
            Arr::set($data, Order::COLUMN_USER_ID, $user->id);
        }

        $order = DB::transaction(function () use ($data) {
            $order = $this->orderRepository->store($data);
            $cart = $this->cartRepository->findOrFail(Arr::get($data, 'cart_id'));

            $products = $cart->products->mapWithKeys(fn (Product $product) => [
                $product->id => [
                    OrderProductPivot::COLUMN_QUANTITY => $product->pivot->{CartProductPivot::COLUMN_QUANTITY},
                    OrderProductPivot::COLUMN_PRICE => $product->price,
                ],
            ]);

            $order->products()->attach($products);
            $cart->products()->detach();
            $cart->delete();

            return $order;
        });

        $order->load('products');

        return $order;
    }
}
