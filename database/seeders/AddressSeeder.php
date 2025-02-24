<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;

final class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Address::factory()
            ->count(count: 5)
            ->has(
                factory: User::factory()
                    ->count(count: 5),
                relationship: 'user',
            )
            ->create();
    }
}
