<?php

declare(strict_types=1);

namespace App\Http\Controllers\api\v1\Like;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Repositories\LikeRepository;
use App\Http\Resources\v1\LikeResource;
use App\Http\Requests\v1\Like\LikeRequest;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Like
 *
 * APIs for like management
 */
class likeController extends Controller
{
    /**
     * LikeController constructor.
     *
     * @param LikeRepository $repository The like repository instance.
     */
    public function __construct(private readonly LikeRepository $repository)
    {
    }

    /**
     * Get the list of likes
     *
     * @header Accept-Language en
     *
     * @apiResource \App\Http\Resources\v1\LikeResource
     * @apiResourceModel \App\Models\Like
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $likes = $this->repository->all();
        return LikeResource::collection($likes);
    }

    /**
     * Get a paginate listing of the like resources.
     *
     * @header Accept-Language en
     *
     * @apiResource \App\Http\Resources\v1\LikeResource
     * @apiResourceModel \App\Models\Like
     *
     * @return AnonymousResourceCollection
     */
    public function paginate(): AnonymousResourceCollection
    {
        $likes = $this->repository->paginate();
        return LikeResource::collection($likes);
    }

    /**
     * Store a new like
     *
     * @apiResource \App\Http\Resources\v1\LikeResource
     * @apiResourceModel \App\Models\Like
     *
     * @param LikeRequest $request
     *
     * @return JsonResponse
     */
    public function store(LikeRequest $request): JsonResponse
    {
        $this->repository->create(
            attributes: $request->validated(),
        );
        return response()->json([
            'message' => 'Like added successfully.',
        ],
            status: JsonResponse::HTTP_CREATED);
    }

    /**
     * Delete a like
     *
     * @apiResource \App\Http\Resources\v1\LikeResource
     * @apiResourceModel \App\Models\Like
     *
     * @param string $id
     *
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $this->repository->delete($id);
        return response()->json(
            ['message' => 'Like deleted successfully.'],
        status: JsonResponse::HTTP_NO_CONTENT);
    }
}
