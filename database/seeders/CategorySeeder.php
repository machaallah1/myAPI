<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

final class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::factory()
            ->count(count: 5)
            ->create()
            ->each(function (Category $category): void {
                //$category->addMediaFromUrl(url: 'https://placehold.co/600x400');
            });
    }
}
