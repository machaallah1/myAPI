<?php

declare(strict_types=1);

namespace App\Http\Controllers\api\v1\Post;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Repositories\PostRepository;
use App\Http\Resources\v1\PostResource;
use App\Http\Requests\v1\Post\PostRequest;
use App\Models\Post;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @group Post
 *
 * APIs for post management
 */
class PostController extends Controller
{
    /**
     * PostController constructor.
     *
     * @param PostRepository $repository The post repository instance.
     */
    public function __construct(private readonly PostRepository $repository)
    {
    }

    /**
     * Get the list of posts
     *
     * @header Accept-Language en
     *
     * @apiResource \App\Http\Resources\v1\PostResource
     * @apiResourceModel \App\Models\Post
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $posts = $this->repository->all();
        return PostResource::collection($posts);
    }

    /**
     * Get a paginate listing of the post resources.
     *
     * @header Accept-Language en
     *
     * @apiResource \App\Http\Resources\v1\PostResource
     * @apiResourceModel \App\Models\Post
     *
     * @return AnonymousResourceCollection
     */
    public function paginate(): AnonymousResourceCollection
    {
        $posts = $this->repository->paginate();
        return PostResource::collection($posts);
    }

    /**
     * Show Post
     *
     * Show the specified resource.
     *
     * @header Accept-Language en
     *
     * @urlParam id string required The ID of the post.
     *
     * @param string $id
     *
     * @return JsonResource
     */
    public function show(string $id): JsonResource
    {
        /** @var \App\Models\Post $post */
        $post = $this->repository->find($id);
        return new PostResource(resource:$post);
    }

    /**
     * Store Post
     *
     * Store a newly created resource in storage.
     *
     * @header Accept-Language en
     *
     * @apiResource \App\Http\Resources\v1\PostResource
     * @apiResourceModel \App\Models\Post
     *
     * @return JsonResponse
     *
     */
    public function store(PostRequest $request): JsonResponse
    {
        $this->repository->create(
            attributes: $request->validated(),
        );
        return response()->json(
            data:[
            'message' => 'Post created successfully.'
        ],
         status: JsonResponse::HTTP_CREATED);
    }

    /**
     * Delete Post
     *
     * Remove the specified resource from storage.
     *
     * @header Accept-Language en
     *
     * @urlParam id string required The ID of the post.
     *
     * @response 204 scenario="Success" {"message": "Post deleted successfully."}
     *
     * @param string $id
     *
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $this->repository->delete($id);
        return response()->json(['message' => 'Post deleted successfully.'],
         status: JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Update Post
     *
     * Update the specified resource in storage.
     *
     * @header Accept-Language en
     *
     * @urlParam id string required The ID of the post.
     *
     * @response 200 scenario="Success" {"message": "Post updated successfully."}
     *
     * @param string $id
     *
     * @return JsonResponse
     */
    public function update(PostRequest $request, string $id): JsonResponse
    {
        $this->repository->update(
            id: $id,
            attributes: $request->validated(),
        );
        return response()->json([
            'message' => 'Post updated successfully.'],
            status: JsonResponse::HTTP_OK);
    }
}
