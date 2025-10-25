<?php

namespace App\Repositories;

use App\Http\Resource\Admin\OrderResource;
use App\Models\Order;
use App\Repositories\Base\BaseRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderRepository extends BaseRepository
{
    protected string $class = Order::class;

    public function store(array $data): Order
    {
        return $this->getModel()->create($data);
    }

    protected function wrapResource(array $items): AnonymousResourceCollection
    {
        return OrderResource::collection($items);
    }
}
