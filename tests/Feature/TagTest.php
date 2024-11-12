<?php

declare(strict_types=1);

use App\Models\Tag;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

describe('test tags', function (): void {
    it('can retrieve tags list', function (): void {
        // Authentifier l'utilisateur si nécessaire
        // authenticateUser();

        $response = getJson(route('tags.index'));

        // Vérifier que la réponse est OK et la structure de la réponse
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'name',
                             'slug',
                             'createdAt',
                         ],
                     ],
                 ]);
    });

    it('can paginate tags', function (): void {
        // Authentifier l'utilisateur si nécessaire
        // authenticateUser();

        $response = getJson(route('tags.paginate', ['perPage' => 15, 'page' => 1]));

        // Vérifier que la réponse est OK et la structure de pagination
        $response->assertOk()
                 ->assertJsonStructure([
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
                 ]);
    });

    it('can create tag', function (): void {
        // Authentifier l'utilisateur si nécessaire
        // authenticateUser();

        $data = [
            'name' => 'Technology',
            'slug' => 'technology',
        ];

        $response = postJson(route('tags.store'), $data);

        // Vérifier que le statut de la réponse est Created (201) et que le tag existe dans la base de données
        $response->assertCreated();
        assertDatabaseHas('tags', $data);
    });

    it('can show a tag', function (): void {
        // Authentifier l'utilisateur si nécessaire
        // authenticateUser();

        $tag = Tag::factory()->create();

        $response = getJson(route('tags.show', ['id' => $tag->id]));

        // Vérifier que la réponse est OK et la structure de la réponse
        $response->assertOk()
                 ->assertJsonStructure([
                     'data' => [
                         'id',
                         'name',
                         'slug',
                         'createdAt',
                     ],
                 ]);
    });

    it('can update a tag', function (): void {
        // Authentifier l'utilisateur si nécessaire
        // authenticateUser();

        $tag = Tag::factory()->create();

        $data = [
            'name' => 'Updated Tag',
            'slug' => 'updated-tag',
        ];

        $response = putJson(route('tags.update', ['id' => $tag->id]), $data);

        // Vérifier que la réponse est OK et que le tag a bien été mis à jour dans la base de données
        $response->assertOk();
        assertDatabaseHas('tags', [
            'id' => $tag->id,
            ...$data,
        ]);
    });

    it('can delete a tag', function (): void {
        // Authentifier l'utilisateur si nécessaire
        // authenticateUser();

        $tag = Tag::factory()->create();

        $response = deleteJson(route('tags.destroy', ['id' => $tag->id]));

        $response->assertNoContent();
        assertDatabaseMissing('tags', [
            'id' => $tag->id,
        ]);
    });
});
