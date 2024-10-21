<?php

namespace Database\Seeders;

use Str;
use App\Models\User;
use Illuminate\Database\Seeder;
use Pest\Support\Str as machrich;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Créer un admin par défaut
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // Mot de passe haché
            'role' => 'admin',
            'phone' => '123456789',
            'image' => 'default-admin.png', // Image par défaut pour l'admin
            'remember_token' => machrich::random(10),
        ]);

        // Créer 10 utilisateurs aléatoires avec des rôles variés
        User::factory()->count(10)->create();
    }

}

