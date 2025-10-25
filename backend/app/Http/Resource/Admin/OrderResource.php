<?php

namespace App\Http\Resource\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            Order::COLUMN_ID => $this->{Order::COLUMN_ID},
            Order::COLUMN_CUSTOMER_PHONE => $this->{Order::COLUMN_CUSTOMER_PHONE},
            Order::COLUMN_CUSTOMER_FIO => $this->{Order::COLUMN_CUSTOMER_FIO},
            Order::COLUMN_STATUS => $this->{Order::COLUMN_STATUS}->name,
            Order::RELATION_PRODUCTS => ProductWithoutCategoryResource::collection($this->{Order::RELATION_PRODUCTS}),
            Order::COLUMN_COMMENTS => $this->{Order::COLUMN_COMMENTS},
        ];
    }
}
