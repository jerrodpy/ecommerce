<?php

namespace App\Http\Resource\Shop;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            Cart::COLUMN_ID => $this->{Cart::COLUMN_ID},
            Cart::COLUMN_GUEST_ID => $this->{Cart::COLUMN_GUEST_ID},
            Cart::COLUMN_USER_ID => $this->{Order::COLUMN_USER_ID},
            Cart::RELATION_PRODUCTS => CartItemResource::collection($this->{Cart::RELATION_PRODUCTS}),
        ];
    }
}
