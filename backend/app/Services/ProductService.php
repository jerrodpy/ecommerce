<?php

namespace App\Services;

use App\Http\Requests\Admin\ProductRequest;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProductService
{
    public const string FOLDER_WITH_PRODUCT_IMAGES = 'products';

    public function __construct(
        private readonly ProductRepository $productRepository
    ) {
    }

    /**
     * @throws Throwable
     */
    public function create(ProductRequest $request): Product
    {
        $payload = $request->validated();
        $image = Arr::pull($payload, Product::COLUMN_IMAGE);
        $categories = Arr::pull($payload, Product::RELATION_CATEGORIES);
        $product = null;

        try {
            $product = $this->productRepository->store($payload);

            if ($categories) {
                $product->categories()->attach($categories);
            }

            $this->updateImage($product, $image);

            return $product;
        } catch (Throwable $exception) {
            $this->deletedImage($product);
            $product?->delete();

            Log::error("An error occurred while creating the product. {$exception->getMessage()}");

            throw $exception;
        }
    }

    /**
     * @throws Throwable
     */
    public function update(ProductRequest $request, Product $product): Product
    {
        $payload = $request->validated();
        $categories = Arr::pull($payload, Product::RELATION_CATEGORIES);
        Arr::pull($payload, Product::COLUMN_IMAGE);

        $product->update($payload);

        if ($categories !== null) {
            $product->categories()->sync($categories);
        }

        $product->load(Product::RELATION_CATEGORIES);

        return $product;
    }

    /**
     * @throws Throwable
     */
    public function uploadImage(ProductRequest $request, Product $product): Product
    {
        $payload = $request->validated();
        $file = Arr::pull($payload, Product::COLUMN_IMAGE);

        try {
            $this->updateImage($product, $file);
            $product->load(Product::RELATION_CATEGORIES);

            return $product;
        } catch (Throwable $exception) {
            Log::error("An error occurred while uploading the product image. {$exception->getMessage()}");

            throw $exception;
        }
    }

    /**
     * @throws Throwable
     */
    public function delete(Product $product): bool
    {
        try {
            $this->deletedImage($product);
            $product?->delete();

            return true;
        } catch (Throwable $exception) {
            return false;
        }
    }

    private function updateImage(Product $product, ?UploadedFile $file): void
    {
        if (!$file) {
            return ;
        }

        $extension = $file->getClientOriginalExtension();
        [$originalName] = explode('.', $file->getClientOriginalName());

        $filename = uniqid() . '_' . $originalName . '.' . $extension;
        $savePath = self::FOLDER_WITH_PRODUCT_IMAGES . DIRECTORY_SEPARATOR . $product->{Product::COLUMN_ID};

        $imageOldUrl = $product->{Product::COLUMN_IMAGE};

        if ($imageOldUrl) {
            Storage::disk()->delete($imageOldUrl);
        }

        $imageUrl = Storage::disk('public')->putFileAs($savePath, $file, $filename);

        $product->update([Product::COLUMN_IMAGE => $imageUrl]);
    }

    private function deletedImage(?Product $product): void
    {
        if (!$product) {
            return;
        }

        $imageUrl = $product->{Product::COLUMN_IMAGE};
        Storage::disk('public')->delete($imageUrl);
        $directory = dirname($imageUrl);

        if (empty(Storage::disk('public')->files($directory)) && empty(Storage::disk('public')->directories($directory))) {
            Storage::disk('public')->deleteDirectory($directory);
        }
    }
}
