<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Address;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Address\StoreRequest;
use App\Http\Requests\v1\Address\UpdateRequest;
use App\Http\Resources\v1\AddressResource;
use App\Models\Address;
use App\Repositories\AddressRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Address
 *
 * APIS for Address management
 *
 * @authenticated
 */
final class AddressController extends Controller
{
    /**
     * AddressController constructor.
     *
     * @param AddressRepository $repository The address repository instance.
     */
    public function __construct(private readonly AddressRepository $repository) {}

    /**
     * Delete Address
     *
     * Delete the specified resource from storage.
     *
     * @header Accept-Language en
     *
     * @urlParam id string required The ID of the address
     *
     * @response 200 scenario="Deleted" {
     *   "message": "Address deleted successfully"
     * }
     *
     * @param string $id
     *
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $this->repository->delete(id: $id);

        return response()->json(
            data: ['message' => __('address.deleted')],
            status: Response::HTTP_OK,
        );
    }

    /**
     * List Address
     *
     * Get a listing of the address resource.
     *
     * @header Accept-Language en
     *
     * @apiResourceCollection \App\Http\Resources\v1\AddressResource
     * @apiResourceModel \App\Models\Address
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $addresses = $this->repository->all();

        return AddressResource::collection($addresses);
    }

    /**
     * Paginate Address
     *
     * Get a paginate listing of the address resource.
     *
     * @header Accept-Language en
     *
     * @urlParam perPage int The number of addresses per page .Example:15
     * @urlParam page int The page number. Example:2
     *
     * @apiResourceCollection \App\Http\Resources\v1\AddressResource
     * @apiResourceModel \App\Models\Address
     *
     * @return AnonymousResourceCollection
     */
    public function paginate(): AnonymousResourceCollection
    {
        $addresses = $this->repository->paginate();

        return AddressResource::collection($addresses);
    }

    /**
     * Show Address
     *
     * Show the specified resource.
     *
     * @header Accept-Language en
     *
     * @urlParam id string required The ID of the address
     *
     * @param string $id
     *
     * @return AddressResource
     */
    public function show(string $id): AddressResource
    {
        /** @var Address $address */
        $address = $this->repository->find($id);

        return new AddressResource(resource: $address);
    }

    /**
     * Store Address
     *
     * Store a newly created resource in storage.
     *
     * @header Accept-Language en
     *
     * @response 201 scenario="Created" {
     *   "message": "Address created successfully"
     * }
     *
     * @param StoreRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $this->repository->create(
            attributes: $request->validated(),
        );

        return response()->json(
            data: [
                'message' => __('address.created'),
            ],
            status: Response::HTTP_CREATED,
        );
    }

    /**
     * Update Address
     *
     * Update the specified resource in storage.
     *
     * @header Accept-Language en
     *
     * @response 200 scenario="Updated"{
     *   "message":"Address updated successfully"
     * }
     *
     * @param UpdateRequest $request
     * @param string $id
     *
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, string $id): JsonResponse
    {
        $this->repository->update(
            id: $id,
            attributes: $request->validated(),
        );
        return response()->json(
            data: [
                'message' => __('address.updated'),
            ],
            status: Response::HTTP_OK,
        );
    }
}
