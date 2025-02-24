<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Address;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\AddressResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Address
 *
 * APIS for Address management
 *
 * @subgroup User Address
 *
 * @subgroupDescription APIs for User Address management
 *
 * @authenticated
 */
final class UserAddressController extends Controller
{
    /**
     * List User Address
     *
     * Get a listing of the provided user address resource.
     *
     * @header Accept-Language en
     *
     * @apiResourceCollection \App\Http\Resources\v1\AddressResource
     * @apiResourceModel \App\Models\Address
     *
     * @return AnonymousResourceCollection
     */
    public function __invoke(User $user): AnonymousResourceCollection
    {
        $addresses = $user->addresses()->get();

        return AddressResource::collection(resource: $addresses);
    }
}
