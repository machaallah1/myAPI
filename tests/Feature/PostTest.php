<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Tag;
use App\Models\Like;
use Nette\Schema\Elements\Structure;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;

describe(description: 'test posts', tests: function (): void {

    it(description: 'can create a post', closure: function (): void {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $data = [
            'title' => 'New Post',
            'content' => 'This is a test post content.',
            'user_id' => $user->id,
            'category_id' => $category->id,
            'slug' => 'new-post',
        ];

        $response = postJson(route('posts.store'), $data);

        $response->assertCreated();
        assertDatabaseHas('posts', $data);
    });

    it('can update a post', function () {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        // Créez un post
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $data = [
            'title' => 'Updated Post Title',
            'content' => 'Updated post content.',
            'user_id' => $user->id,
            'category_id' => $category->id,
        ];

        // Effectuer la requête PUT pour mettre à jour le post
        $response = putJson(route('posts.update', $post->id), $data);

        // Vérifiez que la requête est réussie
        $response->assertOk();

        // Vérifiez que les données du post sont mises à jour dans la base de données
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => $data['title'],
            'content' => $data['content'],
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);
    });


    it(description: 'can delete a post', closure: function (): void {
        $user = User::factory()->create();  // Create a user
        $category = Category::factory()->create();  // Create a category
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $response = deleteJson(route('posts.destroy', $post->id));

        $response->assertNoContent();
        assertDatabaseMissing('posts', ['id' => $post->id]);
    });

    it('can retrieve a post with its relations', function () {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        // Créer un commentaire et le lier au post et à l'utilisateur
        $comment = Comment::create([
            'content' => 'Test comment',
            'post_id' => $post->id,
            'user_id' => $user->id, // Assurez-vous que l'user_id est défini
        ]);


        $response = getJson(route('posts.show', ['id' => $post->id]));

        $response->assertOk()
            ->assertJsonStructure( structure:[
                // 'date'
            ]);
    });

});
