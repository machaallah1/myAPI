<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;

describe(description: 'Test user authentication with OTP via email', tests: function (): void {

    test(description: 'can authenticate and send OTP via email', closure: function (): void {
        Notification::fake(); // Prépare pour simuler les notifications

        $response = postJson(
            uri: '/api/v1/authenticate',
            data: [
                'email' => 'machbetbet@gmail.com',
            ],
        );

        $response->dump();
        // Vérifie si l'utilisateur est créé dans la base de données
        assertDatabaseHas('users', [
            'email' => 'machbetbet@gmail.com',
        ]);

        $response->assertJsonStructure([
                'message',
            ])
            ->assertJson(fn (AssertableJson $json) =>
                $json->whereType('message', 'string')
            );
    })->only();

    test(description: 'can resend OTP code via email to the authenticating user', closure: function (): void {
        Notification::fake();

        postJson(
            uri: '/api/v1/authenticate',
            data: [
                'email' => 'machbetbet@gmail.com',
            ],
        );

        $user = User::first();

        $response = postJson(
            uri: "/api/v1/resend-otp/{$user->id}",
        );

        $response->assertOk()
            ->assertJsonStructure([
                'message',
            ]);
    });

    test(description: 'can authenticate with OTP code sent via email', closure: function (): void {
        postJson(
            uri: '/api/v1/authenticate',
            data: [
                'email' => 'machbetbet@gmail.com',
            ],
        );

        $user = User::first();
        $code = $user->otpCodes->first();

        $response = postJson(
            uri: "/api/v1/otp-login",
            data: [
                'user_id' => $user->id,
                'otp' => $code->otp,
            ],
        );

        $response->assertOk()
            ->assertJsonStructure([
                'data',
                'token',
                'message',
            ]);
    });

    test(description: 'can logout user', closure: function (): void {

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        authenticateUser();

        $response = postJson(uri: '/api/v1/logout', headers: [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertNoContent();

        assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);
    });
});
