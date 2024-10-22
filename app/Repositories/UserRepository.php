<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\DatabaseManager;

final class UserRepository extends Repository
{
    /**
     * Constructor for the UserRepository class.
     */
    public function __construct()
    {
        parent::__construct(
            query: User::query(),
            database: resolve(
                name: DatabaseManager::class,
            ),
        );
    }
}
