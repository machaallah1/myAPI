<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\DatabaseManager;

/**
 * @extends RepositoryContract<Category>
 */
class CategoryRepository extends Repository
{
    /**
     * @param Category $model
     */
    public function __construct(Category $model)
    {
        parent::__construct(
            query: $model::query(),
            database: resolve(
                name: DatabaseManager::class,
            ),
        );
    }
}
