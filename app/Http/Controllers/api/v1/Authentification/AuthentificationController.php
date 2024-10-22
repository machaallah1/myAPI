<?php

declare(strict_types=1);

namespace App\Http\Controllers\api\v1\Authentication;

use Exception;
use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\v1\UserResource;
use App\Repositories\Concerns\SmsContract;
use App\Repositories\AuthenticationRepository;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Requests\v1\Authentication\LoginRequest;
use App\Http\Requests\v1\Authentication\AuthenticateRequest;
use App\Http\Requests\v1\Authentication\LoginWithOtpRequest;

/**
 * @group Authentication
 *
 * APIs for authentication
 */
final class AuthenticationController extends Controller
{
    /**
     * Constructor for the AuthenticationController class.
     *
     * @param AuthenticationRepository $repositoy The authentication repository instance.
     * @param SmsContract $smsRepositoy The SMS repository instance.
     */
    public function __construct(
        private readonly AuthenticationRepository $repositoy,
        private readonly SmsContract $smsRepositoy,
    ) {
        // Inject the authentication repository instance.
    }

    /**
     * Authenticate the user.
     *
     * Authenticates the user and sends an OTP (One-Time Password) to the user.
     * The OTP is sent using the SMS service.
     * Used for login and registration.
     *
     * @header Accept-Language en
     *
     * @apiResource \App\Http\Resources\v1\UserResource
     * @apiResourceModel \App\Models\User
     * @apiResourceAdditional message="An OTP code has been successfully sent"
     *
     * @response 400 scenario="Bad Request" {
     *   "message": "Oops! An error occurred. Please try again."
     * }
     *
     * @param AuthenticateRequest $request The request containing the user's authentication details.
     * @return JsonResource|JsonResponse The user resource or a JSON response indicating the result of the authentication and OTP sending.
     */
    public function authenticate(AuthenticateRequest $request): JsonResource|JsonResponse
    {
        try {
            // Authenticate the user using the repository
            $user = $this->repositoy->authenticate(request: $request);

            // Check if the request has a hash parameter
            $hash = $request->has(key: 'hash') ? $request->hash : '';

            // Send the OTP to the user and get the state
            $state = $this->repositoy->sendOtp(
                phone: $user->phone,
                smsRepository: $this->smsRepositoy,
                hash: $hash,
            );

            // Set the message based on the state
            $message = $state ?
                __(key: 'messages.success.otp')
                : __(key: 'messages.error.general');

            // If the OTP was sent successfully, return the user resource with the message
            if ($state) {
                return UserResource::make($user)
                    ->additional(data: [
                        'message' => $message,
                    ]);
            }

            // If the OTP sending failed, return a JSON response with the error message
            return response()->json(
                data: ['message' => $message],
                status: JsonResponse::HTTP_BAD_REQUEST,
            );
        } catch (Exception $exception) {
            return response()->json(
                data: [
                    'message' => $exception->getMessage(),
                ],
                status: JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
            );
        }
    }

    /**
     * Login
     *
     * Log in a user.
     *
     * @header Accept-Language en
     *
     * @apiResource \App\Http\Resources\v1\UserResource
     * @apiResourceModel \App\Models\User
     * @apiResourceAdditional token="48|78454"
     * @apiResourceAdditional message="You've been logged in successfully."
     *
     * @param  LoginRequest  $request  The request containing the user's login details.
     * @return JsonResource|JsonResponse  The user resource or a JSON response indicating the result of the authentication.
     */
    public function login(LoginRequest $request): JsonResource|JsonResponse
    {
        // Authenticate the user using the repository
        return $this->repositoy->login(request: $request);
    }

    /**
     * OTP Login
     *
     * Log in a user by sending an OTP code to their phone number.
     * This method is used to authenticate a registered user by sending an OTP (One-Time Password)
     * to their phone number. The OTP code is sent using the SMS service.
     *
     * @header Accept-Language en
     *
     * @apiResource \App\Http\Resources\v1\UserResource
     * @apiResourceModel \App\Models\User
     * @apiResourceAdditional token="48|78454"
     * @apiResourceAdditional message="You've been logged in successfully."
     *
     * @response 400 scenario="Bad Request" {
     *   "message": "Your OTP is not correct.",
     *   "action": "Redirect to the login page."
     * }
     *
     * @param LoginWithOtpRequest $request The request containing the user's phone number and OTP code.
     * @return JsonResource|JsonResponse The user resource or a JSON response indicating the result of the authentication.
     */
    public function loginWithOtp(LoginWithOtpRequest $request): JsonResource|JsonResponse
    {
        // Authenticate the user using the repository
        return $this->repositoy->loginWithOtp(request: $request);
    }

    /**
     * Resend OTP code
     *
     * Resends the OTP (One-Time Password).
     *
     * @header Accept-Language en
     *
     * @response 200 scenario="Success" {
     *   "message": "An OTP code has been successfully sent."
     * }
     *
     * @response 400 scenario="Bad Request" {
     *   "message": "Oops! An error occurred. Please try again."
     * }
     *
     * @param  User  $user  The user to resend the OTP to.
     * @return JsonResponse The JSON response indicating the result of the OTP resend.
     */
    public function resendOtp(User $user): JsonResponse
{
    try {
        // Générer un nouvel OTP
        $otp = rand(100000, 999999); // Code OTP de 6 chiffres

        // Enregistrer l'OTP dans la base de données ou dans le cache
        $user->otp_code = $otp;
        $user->save();

        // Envoyer l'OTP par email
        Mail::to($user->email)->send(new OtpMail((string)$otp));

        // Message de succès
        return response()->json([
            'message' => __('messages.success.otp'), // Message de succès
        ], JsonResponse::HTTP_OK);

    } catch (\Exception $e) {
        // Message d'erreur en cas de problème
        return response()->json([
            'message' => __('messages.error.general'),
            'error' => $e->getMessage(),
        ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}


}
