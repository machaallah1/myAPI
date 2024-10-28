<?php

namespace App\Http\Controllers\api\v1\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Http\Resources\v1\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

  /**
     * @group User
     *
     * APIs for user
     *
     * @authenticated
     */
class UserController extends Controller
{
    /**
     * UserController constructor
     *
     * @param UserRepository $repository the user repository instance
     */

    public function __construct(private UserRepository $repository)
    {
    }


    /**
     * Get the list of users
     *
     * @herder Accept-Language en
     *
     * @apiResource \App\Http\Resources\v1\UserResource
     * @apiResourceModel \App\Models\User
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $users = $this->repository->all();
        return UserResource::collection($users);
    }

    /**
     * Get a paginate listing of the user resources.
     *
     * @header Accept-Language en
     *
     * @apiResource \App\Http\Resources\v1\UserResource
     * @apiResourceModel \App\Models\User
     *
     * @return AnonymousResourceCollection
     */
    public function paginate(): AnonymousResourceCollection
    {
        $users = $this->repository->paginate();
        return UserResource::collection($users);
    }

    /**
     * Show User
     *
     * Show the specified resource.
     *
     * @header Accept-Language en
     *
     * @urlParam id string required The ID of the user.
     *
     * @param int $id
     *
     * @return JsonResource
     */
    public function show(string $id): JsonResource
    {
        /** @var \App\Models\User $user */
        $user = $this->repository->find($id);
        return new UserResource($user);
    }

    /**
     * Store User
     *
     * Strore a newly created resource in storage.
     *
     * @header Accept-Language en
     *
     * @response 201 scenario="Created" {"message": "User created successfully."}
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request-> validate();

        $this->repository->create(attributes:$validated);

        /** @var \App\Models\User $user */
        $user = User::query()->where('email', $validated['email'])->first();
        return response()->json(['message' => 'User created successfully.'], status: JsonResponse::HTTP_CREATED);
    }

    /**
     * Update User
     *
     * Update the specified resource in storage.
     *
     * @header Accept-Language en
     *
     * @urlParam id string required The ID of the user.
     *
     * @response 200 scenario="Success" {"message": "User updated successfully."}
     *
     * @param Request $request
     * @param int $id
     *
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate();
        $this->repository->update($id, attributes:$validated);
        return response()->json(['message' => 'User updated successfully.'], status: JsonResponse::HTTP_OK);
    }

    /**
     * Delete User
     *
     * Remove the specified resource from storage.
     *
     * @header Accept-Language en
     *
     * @urlParam id string required The ID of the user.
     *
     * @response 204 scenario="Success" {"message": "User deleted successfully."}
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $this->repository->delete($id);
        return response()->json(['message' => 'User deleted successfully.'], status: JsonResponse::HTTP_NO_CONTENT);
    }
}
