<?php

namespace App\Repositories;

use App\Http\Resource\Admin\OrderResource;
use App\Models\Order;
use App\Repositories\Base\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderRepository extends BaseRepository
{
    protected string $class = Order::class;

    public function store(array $data): Order
    {
        return $this->getModel()->create($data);
    }

    public function paginateForUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->getModel()
            ->newQuery()
            ->where(Order::COLUMN_USER_ID, $userId)
            ->with(Order::RELATION_PRODUCTS)
            ->latest()
            ->paginate($perPage);
    }

    protected function wrapResource(array $items): AnonymousResourceCollection
    {
        return OrderResource::collection($items);
    }
}
