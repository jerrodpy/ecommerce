<?php

namespace App\Repositories;

use App\Http\Resource\CategoryResource;
use App\Models\Category;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;

class CategoryRepository extends BaseRepository
{
    protected string $class = Category::class;

    private bool $withProductsCount = false;

    public function store(array $data): Category
    {
        return $this->getModel()->create($data);
    }

    public function paginateWithProductsCount(array $filter = []): array
    {
        $this->withProductsCount = true;

        return $this->paginate($filter);
    }

    protected function modifyQuery(Builder $builder, array $filterBy = []): Builder
    {
        if ($this->withProductsCount) {
            $builder->withCount(Category::RELATION_PRODUCTS);
            $this->withProductsCount = false;
        }

        $title = Arr::get($filterBy, Category::COLUMN_TITLE);

        return $builder->when($title, fn ($builder) => $builder->where(Category::COLUMN_TITLE, $title));
    }

    protected function wrapResource(array $items): AnonymousResourceCollection
    {
        return CategoryResource::collection($items);
    }
}
