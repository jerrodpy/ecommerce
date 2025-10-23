<?php

namespace App\Repositories;

use App\Http\Resource\Shop\CategoryCollectionResource;
use App\Models\Category;
use App\Repositories\Base\BaseRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryRepository extends BaseRepository
{
    protected string $class = Category::class;

    protected function wrapResource(array $items): AnonymousResourceCollection
    {
        return CategoryCollectionResource::collection($items);
    }
}
