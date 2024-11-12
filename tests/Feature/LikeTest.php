<?php

declare(strict_types=1);

use App\Models\Like;
use App\Models\Post;
use App\Models\User;

use App\Models\Comment;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

describe('test likes', function (): void {

    it('can create a like', function (): void {
        // Créer un utilisateur et un post
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create();

        // Données pour la création du like
        $data = [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'liked' => true,
            'comment_id' => $comment->id,
        ];

        // Effectuer la requête POST pour créer le like
        $response = postJson(route('likes.store'), $data);

        // Vérifier que la requête a créé un like
        $response->assertCreated();

        // Vérifier que le like a été ajouté à la base de données
        assertDatabaseHas('likes', $data);
    });

    it('can delete a like', function (): void {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $like = Like::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        // Effectuer la requête DELETE pour supprimer le like
        $response = deleteJson(route('likes.destroy', $like->id));

        // Vérifier que la requête a bien supprimé le like
        $response->assertNoContent();

        // Vérifier que le like a bien été supprimé de la base de données
        assertDatabaseMissing('likes', ['id' => $like->id]);
    });
});
