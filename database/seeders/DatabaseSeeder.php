<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'last_name' => 'admin',
            'first_name' => 'mach',
            'email' => 'admin@gmail.com',
            'phone' => +22892218208,
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $this->call([
            // AddressSeeder::class,
            // CategorySeeder::class,
            // TagSeeder::class,
            // PostSeeder::class,
            // CommentSeeder::class
        ]);
    }
}
