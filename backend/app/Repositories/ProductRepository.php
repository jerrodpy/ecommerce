<?php

namespace App\Repositories;

use App\Http\Resource\Shop\ProductCollectionResource;
use App\Models\Product;
use App\Repositories\Base\BaseRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductRepository extends BaseRepository
{
    protected string $class = Product::class;

    protected function wrapResource(array $items): AnonymousResourceCollection
    {
        return ProductCollectionResource::collection($items);
    }
}
