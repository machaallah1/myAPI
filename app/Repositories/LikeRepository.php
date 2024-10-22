<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Like;
use Illuminate\Database\DatabaseManager;

class LikeRepository extends Repository
{
    /**
     * Constructor for the LikeRepository class.
     */
    public function __construct()
    {
        parent::__construct(
            query: Like::query(),
            database: resolve(
                name: DatabaseManager::class,
            ),
        );
    }
}
