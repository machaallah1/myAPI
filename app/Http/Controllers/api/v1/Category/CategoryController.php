<?php

declare(strict_types=1);

namespace App\Http\Controllers\api\v1\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\CategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Requests\v1\Category\CategoryRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Category
 *
 * APIs for category management
 *
 */
class CategoryController extends Controller
{
    /**
     * CategoryController constructor.
     *
     * @param \App\Repositories\CategoryRepository $repository The category repository instance.
     *
     */
    public function __construct(private readonly \App\Repositories\CategoryRepository $repository)
    {
    }

    /**
     * Get the list of categories
     *
     * @header Accept-Language en
     *
     * @apiResource \App\Http\Resources\v1\CategoryResource
     * @apiResourceModel \App\Models\Category
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $categories = $this->repository->all();
        return CategoryResource::collection($categories);
    }

    /**
     * Get a paginate listing of the category resources.
     *
     * @header Accept-Language en
     *
     * @apiResource \App\Http\Resources\v1\CategoryResource
     * @apiResourceModel \App\Models\Category
     *
     * @return AnonymousResourceCollection
     */
    public function paginate(): AnonymousResourceCollection
    {
        $categories = $this->repository->paginate();
        return CategoryResource::collection($categories);
    }

    /**
     * Show Category
     *
     * @header Accept-Language en
     *
     * @urlParam id string required The ID of the category.
     *
     * @param string $id
     *
     * @return JsonResource
     */
    public function show(string $id): JsonResource
    {
        $category = $this->repository->find($id);
        return new CategoryResource($category);
    }

    /**
     * Store Category
     *
     * Store a newly created resource in storage.
     *
     * @header Accept-Language en
     *
     * @apiResource \App\Http\Resources\v1\CategoryResource
     * @apiResourceModel \App\Models\Category
     *
     * @response 201 scenario="Success" {"message": "Category created successfully."}
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $this->repository->create(
            attributes: $validated,
        );

        /** @var Category $category */
        $category = Category::query()
            ->where(
                column: 'name',
                operator: '=',
                value: $validated['name'],
            )
            ->first();

        if (isset($validated['image'])) {
            $category->addMediaFromRequest(key: 'image')
                ->toMediaCollection(collectionName: 'categories');
        }

        return response()->json(
            data: [
                'message' => __('category.created'),
            ],
            status: Response::HTTP_CREATED,
        );
    }

    /**
     * Update Category
     *
     * Update the specified resource in storage.
     *
     * @header Accept-Language en
     *
     * @response 200 scenario="Updated"{
     *   "message":"Category updated successfully"
     * }
     */
    public function update(CategoryRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();

        $this->repository->update(
            id: $id,
            attributes: collect($validated)->except('image')->toArray(),
        );

        /** @var Category $category */
        $category = Category::query()
            ->findOrFail(id: $id);

        if (isset($validated['image'])) {
            $category->clearMediaCollection('categories');
            $category->addMediaFromRequest(key: 'image')
                ->toMediaCollection(collectionName: 'categories');
        }

        return response()->json(
            data: [
                'message' => __('category.updated'),
            ],
            status: Response::HTTP_OK,
        );
    }
}
