<?php

declare(strict_types=1);

namespace App\Http\Controllers\api\v1\Comment;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Repositories\CommentRepository;
use App\Http\Resources\v1\CommentResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Comment
 *
 * APIs for comment management
 */
class CommentController extends Controller
{
    /**
     * CommentController constructor.
     *
     * @param  \App\Repositories\CommentRepository  $commentRepository
     */
    public function __construct(private readonly CommentRepository $repository)
    {
    }

    /**
     * Get the list of comments
     *
     * @header Accept-Language en
     *
     * @apiResource \App\Http\Resources\v1\CommentResource
     * @apiResourceModel \App\Models\Comment
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $comments = $this->repository->all();
        return CommentResource::collection($comments);
    }

    /**
     * Get a paginate listing of the comment resources.
     *
     * @header Accept-Language en
     *
     * @apiResource \App\Http\Resources\v1\CommentResource
     * @apiResourceModel \App\Models\Comment
     *
     * @return AnonymousResourceCollection
     */
    public function paginate(): AnonymousResourceCollection
    {
        $comments = $this->repository->paginate();
        return CommentResource::collection($comments);
    }

    /**
     * Show the specified resource.
     *
     * @header Accept-Language en
     *
     * @apiResource \App\Http\Resources\v1\CommentResource
     * @apiResourceModel \App\Models\Comment
     *
     * @urlParam id string required The ID of the comment.
     *
     * @param string $id
     *
     * @return JsonResource
     */
    public function show(string $id): JsonResource
    {
        $comment = $this->repository->find($id);
        return new CommentResource($comment);
    }

    /**
     * Delete the specified resource from storage.
     *
     * @header Accept-Language en
     *
     * @urlParam id string required The ID of the comment.
     *
     * @response 204 scenario="Success" {"message": "Comment deleted successfully."}
     *
     * @param string $id
     *
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $this->repository->delete($id);
        return response()->json(['message' => 'Comment deleted successfully.'], status: JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Store Comment
     *
     * Store a newly created resource in storage.
     *
     * @header Accept-Language en
     *
     * @apiResource \App\Http\Resources\v1\CommentResource
     * @apiResourceModel \App\Models\Comment
     *
     * @response 201 scenario="Success" {"message": "Comment created successfully."}
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate();
        $comment = $this->repository->create(attributes:$validated);
        return response()->json(['message' => 'Comment created successfully.', 'comment' => $comment], status: JsonResponse::HTTP_CREATED);
    }

    /**
     * Update Comment
     *
     * Update the specified resource in storage.
     *
     * @header Accept-Language en
     *
     * @urlParam id string required The ID of the comment.
     *
     * @response 200 scenario="Success" {"message": "Comment updated successfully."}
     *
     * @param Request $request
     * @param string $id
     *
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate();
        $this->repository->update($id, attributes:$validated);
        return response()->json(['message' => 'Comment updated successfully.']);
    }
}
