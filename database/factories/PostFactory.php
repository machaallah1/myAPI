<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'user_id' => User::factory()->create()->id,
            'image' => $this->faker->imageUrl( 300, 300, 'people', true, 'faker'),
            'slug' => $this->faker->slug(),
            'status' => $this->faker->randomElement(['draft', 'published']),
        ];
    }
}
