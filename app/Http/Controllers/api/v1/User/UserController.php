<?php

namespace App\Http\Controllers\api\v1\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Http\Resources\v1\UserResource;
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

}
