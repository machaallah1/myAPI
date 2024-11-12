<?php

declare(strict_types=1);

use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

describe(description: 'Test User', tests: function (): void {

    // It is a good practice to authenticate the user before performing actions
    beforeEach(function () {
        // Optionally uncomment this line if you have an authentication method
        // authenticateUser();
    });

    it(description: 'can retrieve list', closure: function (): void {
        $response = getJson(route('users.index'));

        $response->assertOk()
            ->assertJsonStructure([
                'data',
            ]);
    });

    it('can paginate users', function (): void {
        $response = getJson(route('users.paginate', ['perPage' => 15, 'page' => 1]));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'phone',
                        'role',
                        'createdAt',
                    ],
                ],
                'links', 'meta',
            ]);
    });

    it(
        description: 'can create user',
        closure: function (): void {

        $data = [
            'name' => 'Doe',
            'role' => 'admin',
            'email' => 'johnny@example.com',
            'phone' => '1234567890',
            'password' => bcrypt('password'),
        ];

        $response = postJson(route('users.store'), $data);

        $response->assertCreated();
        assertDatabaseHas('users', $data);
    },
);

    it('can show the user', function (): void {
        $user = User::factory()->create();

        $response = getJson(route('users.show', ['id' => $user->id]));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'phone',
                    'role',
                    'createdAt',
                ],
            ]);
    });

    it('can update a user', function (): void {
        $user = User::factory()->create();
        $data = [
            'name' => 'Doe Updated',
            'role' => 'admin',
            'email' => 'johnny.updated@example.com',
            'phone' => '987456123',
            'password' => bcrypt('newpassword'),
        ];

        $response = putJson(route('users.update', ['id' => $user->id]), $data);

        $response->assertOk();
        assertDatabaseHas('users', [
            'id' => $user->id,
            ...$data,
        ]);
    });

    it('can delete a user', function (): void {
        $user = User::factory()->create();
        $userId = $user->id;

        $response = deleteJson(route('users.destroy', ['id' => $userId]));

        $response->assertNoContent(); // Ensure the response is OK (200)
        assertDatabaseMissing('users', ['id' => $userId]);
    });
});
