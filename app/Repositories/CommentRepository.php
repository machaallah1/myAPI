<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Comment;
use Illuminate\Database\DatabaseManager;

final class CommentRepository extends Repository
{
    /**
     * constructor for the CommentRepository class.
     */
    public function __construct()
    {
        parent::__construct(
            query: Comment::query(),
            database: resolve(
                name: DatabaseManager::class,
            ),
        );
    }
}
