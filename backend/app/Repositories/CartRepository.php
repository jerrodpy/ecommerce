<?php

namespace App\Repositories;

use App\Http\Resource\Shop\CartResource;
use App\Models\Cart;
use App\Models\CartProductPivot;
use App\Repositories\Base\BaseRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CartRepository extends BaseRepository
{
    protected string $class = Cart::class;

    public function store(array $data): Cart
    {
        return $this->getModel()->create($data);
    }

    public function findByGuest(string $guestId): ?Cart
    {
        return $this->getModel()->newQuery()->where(Cart::COLUMN_GUEST_ID, $guestId)->first();
    }

    public function checkProductInCart(int $cartId, int $productId): bool
    {
        return CartProductPivot::where(CartProductPivot::COLUMN_CART_ID, $cartId)
            ->where(CartProductPivot::COLUMN_PRODUCT_ID, $productId)
            ->exists();
    }

    protected function wrapResource(array $items): AnonymousResourceCollection
    {
        return CartResource::collection($items);
    }
}
