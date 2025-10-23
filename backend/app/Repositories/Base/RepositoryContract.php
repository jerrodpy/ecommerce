<?php

namespace App\Repositories\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;

interface RepositoryContract
{
    public function get(int|string $id): ?Model;

    /**
     *
     * @throws ModelNotFoundException
     */
    public function findOrFail(string $id): Model;

    /**
     * @param string[] $columns
     *
     */
    public function all(array $columns = ['*']): Collection;

    public function getLastId(): int|string;

    public function getByIds(array $ids): Collection;

    public function getAutocomplete(string $value): AnonymousResourceCollection;

    public function count(): int;

    public function licenseCount(): int;
}
