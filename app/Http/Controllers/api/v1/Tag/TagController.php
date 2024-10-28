<?php

declare(strict_types=1);

namespace App\Http\Controllers\api\v1\Tag;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Repositories\TagRepository;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\TagResource;
use App\Http\Requests\v1\Tag\TagRequest;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 *  @group Tag
 *
 *  APIs for tag management
 */
class TagController extends Controller
{
    /**TagController constructor.
     *
     * @param  \App\Repositories\TagRepository  $tagRepository
     */
    public function __construct(private TagRepository $repository)
    {
    }

    /**
     * Get the list of tags.
     *
     * @header Accept-Language en
     *
     * @apiResource \App\Http\Resources\v1\TagResource
     * @apiResourceModel \App\Models\Tag
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $tags = $this->repository->all();
        return TagResource::collection($tags);
    }

    /**
     * Get a paginate listing of the tag resources.
     *
     * @header Accept-Language en
     *
     * @apiResource \App\Http\Resources\v1\TagResource
     * @apiResourceModel \App\Models\Tag
     *
     * @return AnonymousResourceCollection
     */
    public function paginate(): AnonymousResourceCollection
    {
        $tags = $this->repository->paginate();
        return TagResource::collection($tags);
    }

    /**
     * Show the specified resource.
     *
     * @header Accept-Language en
     *
     * @apiResource \App\Http\Resources\v1\TagResource
     * @apiResourceModel \App\Models\Tag
     *
     * @urlParam id string required The ID of the tag.
     *
     * @param string $id
     *
     * @return JsonResource
     */
    public function show(string $slug): JsonResource
    {
        /** @var \App\Models\Tag $tag */
        $tag = $this->repository->find($slug);
        return new TagResource($tag);
    }

    /**
     * Store tag
     *
     * Store a newly created resource in storage.
     *
     * @header Accept-Language en
     *
     * @apiResource \App\Http\Resources\v1\TagResource
     * @apiResourceModel \App\Models\Tag
     *
     * @response 201 scenario="Success" {"message": "Tag created successfully."}
     *
     * @param TagRequest $request
     *
     * @return JsonResponse
     */
    public function store(TagRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $tag = $this->repository->create(attributes:$validated);
        return response()->json(['message' => 'Tag created successfully.', 'tag' => $tag], status: JsonResponse::HTTP_CREATED);
    }

    /**
     * Update tag
     *
     * Update the specified resource in storage.
     *
     * @header Accept-Language en
     *
     * @urlParam id string required The ID of the tag.
     *
     * @response 200 scenario="Success" {"message": "Tag updated successfully."}
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
        return response()->json(['message' => 'Tag updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @header Accept-Language en
     *
     * @urlParam id string required The ID of the tag.
     *
     * @response 200 scenario="Success" {"message": "Tag deleted successfully."}
     *
     * @param string $id
     *
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $this->repository->delete($id);
        return response()->json(['message' => 'Tag deleted successfully.']);
    }
}
