<?php

namespace App\Services;

use App\Http\Requests\Shop\AddProductToCartRequest;
use App\Http\Requests\Shop\CartRequest;
use App\Http\Requests\Shop\UpdateProductRequest;
use App\Models\Cart;
use App\Models\CartProductPivot;
use App\Models\Product;
use App\Repositories\CartRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class CartService
{
    public const string FIELD_PRODUCTS = 'products';

    public function __construct(
        private readonly CartRepository $cartRepository
    ) {
    }

    /**
     * @throws \Throwable
     */
    public function deleteProduct(Cart $cart, Product $product): Cart
    {
        $this->checkProductInCart($cart->id, $product->id);
        $cart->products()->detach($product->id);

        return $cart;
    }

    /**
     * @throws \Throwable
     */
    public function updateProducts(UpdateProductRequest $request, Cart $cart, Product $product): Cart
    {
        $this->checkProductInCart($cart->id, $product->id);

        $cart->products()->updateExistingPivot($product->id, [
            CartProductPivot::COLUMN_QUANTITY => $request->get(CartProductPivot::COLUMN_QUANTITY),
            Model::UPDATED_AT => now(),
        ]);

        return $cart;
    }

    public function addProducts(AddProductToCartRequest $request, Cart $cart): Cart
    {
        $products = $request->validated();
        $this->attachProducts($cart, [$products]);

        return $cart->load(Cart::RELATION_PRODUCTS);
    }

    /**
     * @throws \Throwable
     */
    public function add(CartRequest $request): Cart
    {
        $payload = $request->validated();
        $guestId = Arr::get($payload, Cart::COLUMN_GUEST_ID);
        $products = Arr::pull($payload, self::FIELD_PRODUCTS);

        $user = $request->user();

        if ($user) {
            Arr::set($payload, Cart::COLUMN_USER_ID, $user->id);
        }

        $cart = $guestId ? $this->cartRepository->findByGuest($guestId) : null;
        $cart = $cart ?: $this->cartRepository->store($payload);
        $this->attachProducts($cart, $products);

        return $cart->load(Cart::RELATION_PRODUCTS);
    }

    /**
     * @throws \Throwable
     */
    private function checkProductInCart(int $cartId, int $productId): void
    {
        throw_unless(
            $this->cartRepository->checkProductInCart($cartId, $productId),
            ValidationException::withMessages(['The product is not in the cart.']),
        );
    }

    private function attachProducts(Cart $cart, array $products): void
    {
        $existingProducts = $cart->products()->get()->keyBy('id');

        foreach ($products as $item) {
            $productId = Arr::get($item, CartProductPivot::COLUMN_PRODUCT_ID);
            $quantity = Arr::get($item, CartProductPivot::COLUMN_QUANTITY);

            if ($existingProducts->has($productId)) {
                $currentQuantity = $existingProducts->get($productId)->pivot->{CartProductPivot::COLUMN_QUANTITY};
                $cart->products()->updateExistingPivot($productId, [
                    CartProductPivot::COLUMN_QUANTITY => $currentQuantity + $quantity,
                ]);
            } else {
                $cart->products()->attach($productId, [
                    CartProductPivot::COLUMN_QUANTITY => $quantity,
                ]);
            }
        }
    }
}
