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
        $validated = $request->validate();
        $category = $this->repository->create(attributes:$validated);
        return response()->json(['message' => 'Category created successfully.', 'category' => $category], status: JsonResponse::HTTP_CREATED);
    }

    /**
     * Update Category
     *
     * Update the specified resource in storage.
     *
     * @header Accept-Language en
     *
     * @urlParam id string required The ID of the category.
     *
     * @response 200 scenario="Success" {"message": "Category updated successfully."}
     *
     * @param Request $request
     * @param string $id
     *
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate();
        $this->repository->update($id, attributes:$validated);
        return response()->json(['message' => 'Category updated successfully.'], status: JsonResponse::HTTP_OK);
    }

    /**
     * Delete Category
     *
     * Remove the specified resource from storage.
     *
     * @header Accept-Language en
     *
     * @urlParam id string required The ID of the category.
     *
     * @response 204 scenario="Success" {"message": "Category deleted successfully."}
     *
     * @param string $id
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $this->repository->delete($id);
        return response()->json(['message' => 'Category deleted successfully.'], status: JsonResponse::HTTP_NO_CONTENT);
    }
}
