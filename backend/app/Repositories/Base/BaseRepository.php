<?php

namespace App\Repositories\Base;

use App\Repositories\Base\Traits\HasModelTrait;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;

abstract class BaseRepository implements RepositoryContract, PaginableContract
{
    use HasModelTrait;

    protected const AUTO_COMPLETE_COLUMNS = [];

    /**
     * @inheritDoc
     */
    public function all(array $columns = ['*']): \Illuminate\Support\Collection
    {
        return $this->model::all($columns);
    }

    /**
     * @inheritDoc
     */
    public function get(int|string $id): ?Model
    {
        return $this->model::find($id);
    }

    /**
     * @inheritDoc
     */
    public function findOrFail(string $id): Model
    {
        return $this->model::findOrFail($id);
    }

    /**
     * @inheritDoc
     */
    public function getLastId(): string
    {
        $primaryKey = $this->model->getKeyName();

        return $this->getModel()->orderBy($primaryKey, self::SORT_DESK)
            ->limit(1)
            ->first()->{$primaryKey} ?? 0;
    }

    /**
     * @inheritDoc
     */
    public function getByIds(array $ids): Collection
    {
        return $this->getModel()::find($ids);
    }

    /**
     * @inheritDoc
     */
    public function paginate(array $filter = []): array
    {
        $filterBy = Arr::get($filter, self::FIELD_FILTERS, []);
        //Pagination
        $page = Arr::get($filter, self::FIELD_CURRENT_PAGE, self::DEFAULT_PAGE);
        $perPage = Arr::get($filter, self::FIELD_PER_PAGE, self::DEFAULT_PER_PAGE);

        //OrderBy
        $sortOrders = Arr::get($filter, self::FIELD_SORT_ORDER, []);
        $columns = array_flip(Schema::getColumnListing($this->model->getTable()));

        $queryBuilder = $this->sortQueryBuilder($sortOrders, $columns, $filterBy);
        $paginate = $queryBuilder->paginate($perPage, ['*'], self::FIELD_CURRENT_PAGE, $page);

        return $this->preparePaginatorResponse($paginate, $filterBy, $sortOrders);
    }

    /**
     * @inheritDoc
     */
    public function getAutocomplete(string $value): AnonymousResourceCollection
    {
        return $this->wrapResource(
            $this->buildSearchLikeQuery(static::AUTO_COMPLETE_COLUMNS, $value)->get()->all()
        );
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return $this->getModel()::count();
    }

    /**
     * @inheritDoc
     */
    public function licenseCount(): int
    {
        return $this->count();
    }

    /**
     * @throws Exception
     */
    protected function sortQueryBuilder(array $sortOrders, array $columns, array $filterBy): Builder
    {
        $this->validateSortColumns($sortOrders, $columns);

        $queryBuilder = $this->modifyQuery($this->getModel()->newQuery(), $filterBy);

        if ($this->eachHas($sortOrders, [self::FIELD_SORT_ORDER_COLUMN, self::FIELD_SORT_ORDER_ORDER])) {
            foreach ($sortOrders as $sortOrder) {
                $queryBuilder->orderBy(
                    Arr::get($sortOrder, self::FIELD_SORT_ORDER_COLUMN),
                    Arr::get($sortOrder, self::FIELD_SORT_ORDER_ORDER)
                );
            }
        } else {
            $column = $this->model->getAttribute(self::FIELD_NAME) ? self::FIELD_NAME : $this->model->getKeyName();
            $queryBuilder->orderBy($column, $column === $this->model->getKeyName() ? self::SORT_DESK : self::SORT_ASC);
        }

        return $queryBuilder;
    }

    /**
     * Modify paginate query
     *
     */
    protected function modifyQuery(Builder $builder, array $filterBy = []): Builder
    {
        return $builder;
    }

    protected function wrapResource(array $items): AnonymousResourceCollection
    {
        return JsonResource::collection($items);
    }

    protected function buildSearchLikeQuery(array $columns, string $value): Builder
    {
        return $this->getModel()::query()->where(function (Builder $builder) use ($columns, $value) {
            foreach ($columns as $field) {
                $builder->orWhere($field, 'like', "%{$value}%");
            }
        });
    }

    /**
     * @param array<int, array<string, string>> $sortOrders
     *
     * @throws Exception
     */
    protected function validateSortColumns(array $sortOrders, array $columns): void
    {
        foreach ($sortOrders as $i => $item) {
            if (!isset($columns[$item['column']])) {
                throw new Exception('exceptions column_from_the_sort_order_doesnt_exist');
            }
        }
    }

    private function eachHas(array $array, array $keys): bool
    {
            $result = false;

            foreach ($array as $key => $item) {
                if (is_array($item)) {
                    if (Arr::has($item, $keys)) {
                        $result = true;
                    } else {
                        $result = false;

                        break;
                    }
                } else {
                    throw new Exception('exceptions element must be array');
                }
            }

            return $result;
    }

    private function preparePaginatorResponse(
        \Illuminate\Contracts\Pagination\LengthAwarePaginator $paginator,
        array $filterBy = [],
        array $sortOrders = []
    ): array {
        return [
            self::FIELD_TOTAL => $paginator->total(),
            self::FIELD_LAST_PAGE => $paginator->lastPage(),
            self::FIELD_PER_PAGE => (int)$paginator->perPage(),
            self::FIELD_CURRENT_PAGE => $paginator->currentPage(),
            self::FIELD_SORT_ORDER => $sortOrders,
            self::FIELD_FILTERS => $filterBy,
            self::FIELD_ITEMS => $this->wrapResource($paginator->items()),
        ];
    }
}
