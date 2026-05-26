<?php

namespace App\Repositories;

use App\Http\Resource\Shop\ProductCollectionResource;
use App\Models\CategoryProduct;
use App\Models\Product;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;

class ProductRepository extends BaseRepository
{
    public const string FIELD_MIN = '_min';

    public const string FIELD_MAX = '_max';

    protected string $class = Product::class;

    public function store(array $data): Product
    {
        return $this->getModel()->create($data);
    }

    protected function modifyQuery(Builder $builder, array $filterBy = []): Builder
    {
        $title = Arr::get($filterBy, Product::COLUMN_TITLE);
        $categoryId = Arr::get($filterBy, CategoryProduct::COLUMN_CATEGORY_ID);
        $description = Arr::get($filterBy, Product::COLUMN_DESCRIPTION);
        $priceMin = Arr::get($filterBy, Product::COLUMN_PRICE .  self::FIELD_MIN);
        $priceMax = Arr::get($filterBy, Product::COLUMN_PRICE .  self::FIELD_MAX);

        return $builder
            ->when($title, fn ($builder) => $builder->whereLike(Product::COLUMN_TITLE, "%$title%"))
            ->when($description, fn ($builder) => $builder->where(Product::COLUMN_DESCRIPTION, $description))
            ->when($priceMin, fn ($builder) => $builder->where(Product::COLUMN_PRICE, '>=', $priceMin))
            ->when($priceMax, fn ($builder) => $builder->where(Product::COLUMN_PRICE, '<=', $priceMax))
            ->when(
                $categoryId,
                fn (Builder $builder) => $builder
                    ->whereHas(
                        Product::RELATION_CATEGORIES,
                        fn (Builder $builder) => $builder
                            ->where(CategoryProduct::COLUMN_CATEGORY_ID, $categoryId)
                )
        );
    }

    protected function wrapResource(array $items): AnonymousResourceCollection
    {
        return ProductCollectionResource::collection($items);
    }
}
