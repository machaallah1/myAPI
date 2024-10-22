<?php

declare(strict_types=1);

namespace App\Repositories\Concerns;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @property-read Builder $query
 * @property-read DatabaseManager $database
 */
interface RepositoryContract
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, \Illuminate\Database\Eloquent\Model>
     */
    public function all(): Collection;

    /**
     * @param array<string,mixed> $attributes
     *
     * @return void
     */
    public function create(array $attributes): void;

    /**
     * @param string $id
     *
     * @return void
     */
    public function delete(string $id): void;

    /**
     * @return Model|null
     */
    public function find(string $id): object|null;

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, \Illuminate\Database\Eloquent\Model>|\Illuminate\Pagination\LengthAwarePaginator<\Illuminate\Database\Eloquent\Model>
     */
    public function paginate(): Collection|LengthAwarePaginator;

    /**
     * @param string $id
     * @param array<string,mixed> $attributes
     *
     * @return void
     */
    public function update(string $id, array $attributes): void;
}
