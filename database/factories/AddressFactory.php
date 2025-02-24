<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Address>
 */
final class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'place_name' => $this->faker->company(), // Nom de lieu réaliste
            'longitude' => $this->faker->longitude(),
            'latitude' => $this->faker->latitude(),
            'formated_address' => $this->faker->address(),
            'street_name' => $this->faker->streetName(),
            'user_id' => User::factory(), // L'utilisateur associé est créé par la factory
        ];
    }
}
