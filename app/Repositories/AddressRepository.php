<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Address;
use Illuminate\Database\DatabaseManager;

final class AddressRepository extends Repository
{
    /**
     * Constructor for the AddressRepository class.
     */
    public function __construct()
    {
        parent::__construct(
            query: Address::query(),
            database: resolve(
                name: DatabaseManager::class,
            ),
        );
    }
}
