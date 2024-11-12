<?php

declare(strict_types=1);

use App\Models\Comment;
use App\Models\User;
use App\Models\Post;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

describe('test comments', function () {

    it('can create a comment', function () {
        // Créer un utilisateur et un post pour le test
        $user = User::factory()->create();
        $post = Post::factory()->create();

        // Données du commentaire
        $data = [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'content' => 'This is a test comment.',
        ];

        // Créer le commentaire
        $response = postJson(route('comments.store'), $data);

        // Vérifier que la réponse est une création réussie
        $response->assertCreated();

        // Vérifier que le commentaire a bien été enregistré dans la base de données
        assertDatabaseHas('comments', $data);
    });

    it('can retrieve a comment', function () {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        // Créer un commentaire
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'content' => 'This is a test comment.',
        ]);

        $response = getJson(route('comments.show', $comment->id));

        // Vérifier que la réponse est OK et que la structure JSON est correcte
        $response->assertOk()
            ->assertJsonStructure([
                // 'data'
            ]);
    });


    it('can update a comment', function () {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        // Créer un commentaire
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'content' => 'This is a test comment.',
        ]);
        $data = [
            'content' => 'This is an updated comment.',
            'user_id' => $user->id,
            'post_id' => $post->id,
        ];

        $response = putJson(route('comments.update', $comment->id), $data);

        $response->assertOk();

        assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => $data['content'],
        ]);
    });

    it('can delete a comment', function () {
        // Créer un utilisateur et un post pour le test
        $user = User::factory()->create();
        $post = Post::factory()->create();

        // Créer un commentaire
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'content' => 'This is a test comment.',
        ]);

        // Supprimer le commentaire
        $response = deleteJson(route('comments.destroy', $comment->id));

        // Vérifier que la réponse est une suppression réussie (sans contenu)
        $response->assertNoContent();

        // Vérifier que le commentaire a bien été supprimé de la base de données
        assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    });
});
