<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Database\DatabaseManager;


final class TagRepository extends Repository
{
    /**
     * @param Tag $model
     */
    public function __construct(Tag $model)
    {
        parent::__construct(
            query: $model::query(),
            database: resolve(
                name: DatabaseManager::class,
            ),
        );
    }
}
