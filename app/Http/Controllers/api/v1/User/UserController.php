<?php

namespace App\Http\Controllers\api\v1\User;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Http\Resources\v1\UserResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\v1\User\UserRequest;
use App\Repositories\AddressRepository;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

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
* @param AddressRepository  $addressRepository The user profile repository instance.
     */

    public function __construct(private UserRepository $repository,private AddressRepository $addressRepository) {}


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
  public function store(UserRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {

            $validated = $request->validated();

            $user = $this->repository->store(
                attributes: collect($validated)->except(['address'])->toArray(),
            );
            /** @var User $user */
            $user = User::query()
                ->where(
                    column: 'phone',
                    operator: '=',
                    value: $validated['phone'],
                )
                ->first();

            if (isset($validated['image'])) {
                $user->addMediaFromRequest(key: 'image')
                    ->toMediaCollection(collectionName: 'users');
            }

            if (isset($validated['address'])) {
                $validatedAddress = $validated['address'];
                $validatedAddress['user_id'] = $user->id;

                $this->addressRepository->create($validatedAddress);
            }

            DB::commit();
            return response()->json(
                data: [
                    'message' => __('user.created'),
                ],
                status: JsonResponse::HTTP_CREATED,
            );
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(
                data:[
                    'message' => __('user.creation_failed'),
                    'error' => $e->getMessage(),
                ],
                status: JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
            );
        }
    }


   /**
     * Update User
     *
     * Update the specified resource in storage.
     *
     * @header Accept-Language en
     *
     * @response 200 scenario="Updated"{
     * "message":"User updated successfully"
     *  "data": {
     *     // user and address details here
     *   }
     *
     * }
     *
     * @param UpdateRequest $request
     * @param string $id
     *
     * @return JsonResponse
     */
    public function update(UserRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();

        $this->repository->update(
            id: $id,
            attributes: collect($validated)->except(['image', 'address'])->toArray(),
        );

        /** @var User $user */
        $user = User::query()
            ->findOrFail(id: $id);

        if (isset($validated['image'])) {
            $user->clearMediaCollection('users');
            $user->addMediaFromRequest(key: 'image')
                ->toMediaCollection(collectionName: 'users');
        }

        if (isset($validated['address'])) {
            $addressData = array_merge($validated['address'], ['user_id' => $user->id]);

            $user->addresses()->updateOrCreate(
                ['user_id' => $user->id],
                $addressData,
            );
        }
        return response()->json(
            data: [
                'message' => __('user.updated'),
                'data' => new UserResource($user),
            ],
            status: JsonResponse::HTTP_OK,
        );
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
