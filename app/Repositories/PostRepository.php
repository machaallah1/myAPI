<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Post;
use Illuminate\Database\DatabaseManager;

final class PostRepository extends Repository
{
    /**
     * @param Post $model
     */
    public function __construct(Post $model)
    {
        parent::__construct(
            query: $model::query(),
            database: resolve(
                name: DatabaseManager::class,
            )
        );
    }
}
