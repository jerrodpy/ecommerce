<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Shop\AddProductToCartRequest;
use App\Http\Requests\Shop\CartRequest;
use App\Http\Requests\Shop\UpdateProductRequest;
use App\Http\Resource\Shop\CartResource;
use App\Models\Cart;
use App\Models\Product;
use App\Repositories\CartRepository;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartController extends BaseController
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly CartRepository $cartRepository,
    ) {
    }

    public function show(Request $request)
    {
        $guestId = $request->query(Cart::COLUMN_GUEST_ID);

        $cart = $guestId
            ? $this->cartRepository->findByGuest($guestId)
            : null;

        $this->setData($cart ? CartResource::make($cart->load(Cart::RELATION_PRODUCTS)) : null);

        return $this->sendResponse();
    }

    public function store(CartRequest $request)
    {
        $cart = $this->cartService->add($request);

        $this->setData(CartResource::make($cart));
        $this->setStatusCode(Response::HTTP_CREATED);
        $this->setMessage('The product has been successfully added to your cart');

        return $this->sendResponse();
    }

    public function addProduct(Cart $cart, AddProductToCartRequest $request)
    {
        $cart = $this->cartService->addProducts($request, $cart);

        $this->setData(CartResource::make($cart));

        return $this->sendResponse();
    }

    public function updateProduct(UpdateProductRequest $request, Cart $cart, Product $product)
    {
        $cart = $this->cartService->updateProducts($request, $cart, $product);

        $this->setData(CartResource::make($cart));

        return $this->sendResponse();
    }

    public function deleteProduct(Cart $cart, Product $product)
    {
        $cart = $this->cartService->deleteProduct($cart, $product);

        $this->setData(CartResource::make($cart));

        return $this->sendResponse();
    }
}
