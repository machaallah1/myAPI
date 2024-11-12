<?php

declare(strict_types=1);

use App\Models\Category;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

describe(description: 'test categories', tests: function (): void {
    it(description: 'can retrieve categories list', closure: function (): void {
        // authenticateUser();

        $response = getJson(
            uri: route(
                name: 'categories.index',
            ),
        );

        $response->assertOk()
            ->assertJsonStructure(
                structure: [
                    // 'data',
                ],
            );
    });

    it(description: 'can paginate categories', closure: function (): void {
        // authenticateUser();

        $response = getJson(
            uri: route(
                name: 'categories.paginate',
                parameters: [
                    'perPage' => 15,
                    'page' => 1,
                ],
            ),
        );

        $response->assertOk()
            ->assertJsonStructure(
                structure: [
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'slug',
                            'createdAt',
                        ],
                    ],
                    'links',
                    'meta',
                ],
            );
    });

    it(description: 'can create category', closure: function (): void {
        // authenticateUser();

        $data = [
            'name' => 'Electronics',
            'slug' => 'electronics',
        ];

        $response = postJson(
            uri: route(
                name: 'categories.store',
            ),
            data: $data,
        );

        $response->assertCreated();
        assertDatabaseHas(
            table: 'categories',
            data: $data,
        );
    });

    it(description: 'can show a category', closure: function (): void {
        // authenticateUser();

        $category = Category::factory()->create();

        $response = getJson(
            uri: route(
                name: 'categories.show',
                parameters: [
                    'id' => $category->id,
                ],
            ),
        );

        $response->assertOk()
            ->assertJsonStructure(
                structure: [
                    'data' => [
                        'id',
                        'name',
                        'slug',
                        'createdAt',
                    ],
                ],
            );
    });

    it(description: 'can update a category', closure: function (): void {
        // authenticateUser();

        $category = Category::factory()->create();

        $data = [
            'name' => 'Updated Category',
            'slug' => 'updated-category',
        ];

        $response = putJson(
            uri: route(
                name: 'categories.update',
                parameters: [
                    'id' => $category->id,
                ],
            ),
            data: $data,
        );

        $response->assertOk();
        assertDatabaseHas(
            table: 'categories',
            data: [
                'id' => $category->id,
                ...$data,
            ],
        );
    });

    it(description: 'can delete a category', closure: function (): void {
        // authenticateUser();

        $category = Category::factory()->create();

        $response = deleteJson(
            uri: route(
                name: 'categories.destroy',
                parameters: [
                    'id' => $category->id,
                ],
            ),
        );

        $response->assertNoContent();
        assertDatabaseMissing(
            table: 'categories',
            data: [
                'id' => $category->id,
            ],
        );
    });
});
