<?php

namespace App\Http\Resource\Shop;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'product_id' => $this->resource->pivot->product_id,
            'quantity'   => $this->resource->pivot->quantity,
            'product'    => [
                Product::COLUMN_ID    => $this->resource->{Product::COLUMN_ID},
                Product::COLUMN_TITLE => $this->resource->{Product::COLUMN_TITLE},
                Product::COLUMN_PRICE => $this->resource->{Product::COLUMN_PRICE},
                Product::COLUMN_IMAGE => $this->resource->{Product::COLUMN_IMAGE},
            ],
        ];
    }
}
