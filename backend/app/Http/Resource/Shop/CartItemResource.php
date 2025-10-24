<?php

namespace App\Http\Resource\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'product_id' => $this->resource->pivot->product_id,
            'quantity' => $this->resource->pivot->quantity,
        ];
    }
}
