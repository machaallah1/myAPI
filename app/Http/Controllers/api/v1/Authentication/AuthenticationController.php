<?php

declare(strict_types=1);

namespace App\Http\Controllers\api\v1\Authentication;

use App\Models\User;
use App\Mail\OtpMail;
use App\Models\OtpCode;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
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
     * @param AuthenticationRepository $repository The authentication repository instance.
     */
    public function __construct(
        private readonly AuthenticationRepository $repository,
        // private readonly SmsContract $smsRepositoy,
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
        // Authentifier l'utilisateur en utilisant le repository
        $user = $this->repository->authenticate(request: $request);

        // Vérifier si l'utilisateur existe
        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non trouvé.',
            ], 404);
        }

        // Générer un OTP (vous pouvez adapter cette logique)
        $otp = rand(100000, 999999); // Code OTP de 6 chiffres

        // Optionnel : enregistrer l'OTP dans la base de données si vous le souhaitez
        OtpCode::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'expire_at' => now()->addMinutes(10),
        ]);

        // Envoyer l'OTP par email
        Mail::to($user->email)->send(new OtpMail((string)$otp));

        return response()->json([
            'message' => 'OTP envoyé avec succès à l\'adresse email.',
        ], 200);

    } catch (\Exception $e) {
        // Gérer les exceptions et renvoyer un message d'erreur
        return response()->json([
            'message' => 'Une erreur s\'est produite lors de l\'authentification : ' . $e->getMessage(),
        ], 500);
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
        return $this->repository->login(request: $request);
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
         return $this->repository->loginWithOtp(request: $request);
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

/**
 * logout
 *
 * Log out a user.
 *
 * @header Accept-Language en
 *
 * @apiResource \App\Http\Resources\v1\UserResource
 * @apiResourceModel \App\Models\User
 * @apiResourceAdditional token="48|78454"
 * @apiResourceAdditional message="You've been logged out successfully."
 *
 * @param  User  $user  The user to log out.
 * @return JsonResponse The JSON response indicating the result of the logout.
 */
public function logout(): JsonResponse
{
    return $this->repository->logout();
}

}
