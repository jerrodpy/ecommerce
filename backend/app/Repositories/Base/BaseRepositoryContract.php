<?php

namespace App\Repositories\Base;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryContract
{
    public function all(array $columns = ['*'], array $relations = []): Collection;

    public function find(int $id, array $columns = ['*'], array $relations = []): ?Model;

    public function findOrFail(int $id, array $columns = ['*'], array $relations = []): Model;

    public function findBy(string $field, mixed $value, array $columns = ['*']): ?Model;

    public function create(array $data): Model;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function count(array $filters = []): int;

    public function exists(int $id): bool;
}
