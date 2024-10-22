<?php

declare(strict_types=1);

namespace App\Http\Controllers\api\v1\Authentication;

use Exception;
use App\Models\User;
use App\Mail\OtpMail;
use App\Models\OtpCode;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\v1\UserResource;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\AuthenticationRepository;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Requests\v1\Authentication\LoginRequest;
use App\Http\Requests\v1\Authentication\AuthenticateRequest;
use App\Http\Requests\v1\Authentication\LoginWithOtpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Js;

/**
 * @group Authentication
 *
 * APIs for user authentication
 *
 */
final class AuthenticationController extends Controller
{
    public function __construct(
        private readonly AuthenticationRepository $repository,
        // private readonly SmsContract $smsRepository,
    ) {
        // Dependency injection for repositories
    }

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
     * @param AuthenticateRequest $request
     * @return JsonResponse
     */
    public function authenticate(AuthenticateRequest $request): JsonResponse
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
            }

            // Generate OTP
            $otp = rand(100000, 999999);
            $user->otp_code = $otp;
            $user->save();

            // Send OTP via email
            Mail::to($user->email)->send(new OtpMail((string)$otp));

            return response()->json([
                'message' => 'An OTP has been sent to your email address',
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while sending the OTP.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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

    // Vérifier si l'utilisateur a été trouvé
    if (!$user) {
        throw new \Exception(__('messages.error.user_not_found')); // Optionnel : Gérer le cas où l'utilisateur n'existe pas
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
    // Validate the request
    $validated = $request->validated();

    // Attempt to authenticate the user
    if (Auth::attempt($validated)) {
        // Return a JSON response indicating the authentication failure
        return response()->json(
            [
                'message' => __('auth.failed'), // Utilisez la fonction __() pour la traduction
            ],
            JsonResponse::HTTP_UNAUTHORIZED // Indiquer que l'authentification a échoué
        );
    }

    // Get the authenticated user
    /** @var User $user */
    $user = Auth::user();

    // Generate a token for the user
    $accessToken = $user->createToken(config('app.name'))->plainTextToken;

    // Return a JSON response containing the user resource and the token
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
        // Request validation
        $validated = $request->validated();

        // Find the OTP code with the given user ID and OTP code
        $otpCode = OtpCode::query()
            ->where('user_id', $validated['user_id'])
            ->where('otp', $validated['otp'])
            ->first();

        // Check if the OTP code is valid
        if ($otpCode && $this->verifyOtp($otpCode)) {
            /** @var User $user */
            $user = User::query()->find($validated['user_id']);

            // Expire the OTP
            $otpCode->update(['expire_at' => now()]);

            // Log the user in
            auth()->login($user);
            $token = $user->createToken(config('app.name'))->plainTextToken;

            // Return the user resource with the token and success message
            return UserResource::make($user)->additional([
                'token' => $token,
                'message' => __('messages.success.login'),
            ]);
        }

        // Return a JSON response indicating the OTP code is invalid or expired
        return response()->json([
            'message' => $otpCode
                ? __('otp.expired') // Si le code OTP a été trouvé mais est expiré
                : __('messages.error.otp'), // Si le code OTP est incorrect
            'action' => __('messages.action.login'),
        ], JsonResponse::HTTP_BAD_REQUEST);
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
        $user = auth()->user();

        if ($user) {
            $user->tokens()->delete();
        }
        return response()->json(
            data:[
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

            // Optionnellement, enregistrez l'OTP dans la base de données
            $otpCode = OtpCode::create([
                'email' => $email,
                'code' => $otp,
            ]);

            // Envoyer l'OTP par e-mail
            Mail::to($email)->send(new OtpMail((string)$otp));

            return true; // Indique que l'envoi a réussi
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
        if ($otpCode->expire_at < now()) {
            return response()->json([
                'message' => __('otp.expired'),
                'action' => __('messages.action.resend'),
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
        return true;
    }


}
