<?php

declare(strict_types=1);

namespace  App\Repositories;

use Exception;
use App\Models\User;
use App\Mail\OtpMail;
use App\Models\OtpCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\v1\UserResource;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\Concerns\AuthenticationContract;
use App\Http\Requests\v1\Authentication\LoginWithOtpRequest;

/**
 * @group Authentication
 *
 * APIs for user authentication
 *
 */
final class AuthenticationRepository implements AuthenticationContract
{
    /**
     * Authenticate the user and send OTP.
     *
     * @header Accept-Language en
     * @apiResource \App\Http\Resources\v1\UserResource
     * @apiResourceModel \App\Models\User
     * @apiResourceAdditional message="An OTP code has been successfully sent"
     * @response 400 scenario="Bad Request" {"message": "Oops! An error occurred. Please try again."}
     * @unauthenticated
     *
     * @param FormRequest $request
     * @return JsonResponse
     */
    public function authenticate(FormRequest $request): User
    {
        // Validate the request
        $validated = $request->validated();
        /**
         * @var User|null $user
         */
        $user = null;

        $user = User::query()->where('email', $validated['email'])->first();

        if (null === $user) {
            $user = User::query()->create($validated);
        }
        return $user;
    }

    /**
     * Generates an OTP code for a given user
     *
     * @param string $email
     * @return OtpCode
     */
    public function generateOtp(string $email): OtpCode
    {
        // Trouver l'utilisateur avec l'email donné
        $user = User::query()
            ->where('email', $email)
            ->first();

        $otpCode = OtpCode::query()
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        if ($otpCode && $otpCode->expire_at > now()) {
            return $otpCode;
        }

        // Créer un nouvel OTP pour l'utilisateur
        $otpCode = OtpCode::query()->create([
            'user_id' => $user->id,
            'otp' => rand(100000, 999999), // Générer un code OTP aléatoire de 6 chiffres
            'expire_at' => now()->addMinutes(10), // Définir l'heure d'expiration à 10 minutes à partir de maintenant
        ]);

        // Envoyer l'OTP par email
        try {
            Mail::to($user->email)->send(new OtpMail((string)$otpCode->otp));
        } catch (\Exception $e) {
            // Gérer l'erreur d'envoi d'email
            throw new \Exception(__('messages.error.email_failed'), 0, $e);
        }

        return $otpCode; // Retourner l'OTP
    }



    /**
     * Log in the user with a given email and password.
     *
     * This function attempts to authenticate the user with the provided email and password.
     * If the authentication is successful, it generates a token for the user and returns
     * a JSON response containing the user resource and the token. If the authentication fails,
     * it returns a JSON response indicating the failure.
     *
     * @param  FormRequest  $request  The request containing the user's email and password.
     * @return JsonResource|JsonResponse The user resource with the token or a JSON response indicating the failure.
     */
    public function login(FormRequest $request): JsonResource|JsonResponse
    {
        $validated = $request->validated();

        // Tenter d'authentifier l'utilisateur avec les informations fournies
        if (! auth()->guard()->attempt(credentials: $validated)) {
            // Retourner une réponse JSON indiquant l'échec de l'authentification
            return response()->json(
                [
                    'message' => __('auth.failed'), // Utiliser la fonction __() pour la traduction
                ],
                JsonResponse::HTTP_UNAUTHORIZED // Indiquer que l'authentification a échoué avec un code 401
            );
        }

        // Récupérer l'utilisateur authentifié
        /** @var User $user */
        $user = Auth::user();

        // Générer un token d'accès pour l'utilisateur
        $accessToken = $user->createToken(config('app.name'))->plainTextToken;

        // Retourner une réponse JSON avec les informations de l'utilisateur et le token
        return UserResource::make($user)
            ->additional([
                'token' => $accessToken,
                'message' => __('messages.success.login'), // Message de succès
            ]);
    }

    /**
     * OTP Login.
     *
     * @header Accept-Language en
     * @apiResource \App\Http\Resources\v1\UserResource
     * @apiResourceModel \App\Models\User
     * @apiResourceAdditional token="48|78454" message="You've been logged in successfully."
     * @response 400 scenario="Bad Request" {"message": "Your OTP is not correct.", "action": "Redirect to the login page."}
     * @unauthenticated
     *
     * @param LoginWithOtpRequest $request
     * @return JsonResource|JsonResponse
     */
    public function loginWithOtp(FormRequest $request): JsonResource|JsonResponse
{
    // Validation des données de la requête
    $validated = $request->validated();

    // Recherche du code OTP dans la base de données
    $otpCode = OtpCode::query()
        ->where('user_id', $validated['user_id'])
        ->where('otp', $validated['otp'])
        ->first();

    // Vérification si le code OTP existe
    if (!$otpCode) {
        // Si le code OTP est introuvable
        return response()->json([
            'message' => __('messages.error.otp_not_found'), // Message pour code OTP introuvable
            'action' => __('messages.action.login'),
        ], JsonResponse::HTTP_BAD_REQUEST); // Code d'état 400 : mauvaise requête
    }

    // Vérification de la validité du code OTP
    if (!$this->verifyOtp($otpCode)) {
        // Si la vérification du code OTP échoue
        return response()->json([
            'message' => __('messages.error.invalid_otp'),
            'action' => __('messages.action.login'),
        ], JsonResponse::HTTP_UNAUTHORIZED);
    }

    // Vérification si le code OTP est expiré
    if ($otpCode->expire_at && $otpCode->expire_at < now()) {
        // Si le code OTP a expiré
        return response()->json([
            'message' => __('otp.expired'),
            'action' => __('messages.action.login'),
        ], JsonResponse::HTTP_GONE); // Code d'état 410 : code OTP expiré
    }

    // Trouver l'utilisateur avec l'ID fourni
    /** @var User $user */
    $user = User::query()->find($validated['user_id']);

    // Si l'utilisateur n'est pas trouvé
    if (!$user) {
        return response()->json([
            'message' => __('messages.error.user_not_found'), // Message pour utilisateur non trouvé
            'action' => __('messages.action.login'),
        ], JsonResponse::HTTP_NOT_FOUND); // Code d'état 404 : non trouvé
    }

    // Expirer le code OTP (mise à jour)
    $otpCode->update(['expire_at' => now()]);

    // Authentification de l'utilisateur
    Auth::login($user);

    // Création du token d'accès
    $token = $user->createToken(config('app.name'))->plainTextToken;

    // Retourner les données de l'utilisateur avec le token
    return UserResource::make($user)->additional([
        'token' => $token,
        'message' => __('messages.success.login'), 
    ]);
}

    /**
     * Logout the user.
     *
     * @header Accept-Language en
     * @response 200 scenario="Logout" {"message": "You've been logged out successfully."}
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $user = Auth::user();
        if ($user) {
            $user->tokens()->delete();
        }
        return response()->json(
            data: [
                'message' => __('messages.success.logout')
            ],
            status: JsonResponse::HTTP_OK
        );
    }

    /**
     * Resend OTP code.
     *
     * @header Accept-Language en
     * @response 200 scenario="Success" {"message": "An OTP code has been successfully sent."}
     * @response 400 scenario="Bad Request" {"message": "Oops! An error occurred. Please try again."}
     * @unauthenticated
     *
     * @param User $user
     * @return JsonResponse
     */
    public function sendOtp(string $email): bool
    {
        try {
            // Générer un OTP (vous pouvez adapter cette logique)
            $otp = rand(100000, 999999); // Code OTP de 6 chiffres

            $user = User::where('email', $email)->first();
            // Envoyer l'OTP par e-mail
            Mail::to($user->email)->send(new OtpMail((string)$otp));

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * Check if a the provided otp code is valid.
     *
     * @param OtpCode $otpCode
     * @return JsonResponse|bool
     */
    public function verifyOtp(OtpCode $otpCode): JsonResponse|bool
    {
        // check if the current time is before the expiration time
        return $otpCode->expire_at->isFuture();
    }
}
