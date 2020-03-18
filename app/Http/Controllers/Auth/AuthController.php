<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ConflictException;
use App\Exceptions\NotFoundException;
use App\Exceptions\ServiceUnavailableException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User\User;
use Carbon\Carbon;
use DB;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\JsonResponse;
use JWTAuth;
use Log;
use Password;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * @OA\Post(path="/login", tags={"Auth"},
     *     @OA\RequestBody(required=true,
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(type="object",
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="password", description="Min length 8", type="string"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(response="200", description="Success",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(@OA\Property(property="token", type="string"))
     *         ),
     *     ),
     *     @OA\Response(response="422", description="Unprocessable Entity",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(@OA\Property(property="errors", type="array", @OA\Items(type="string")))
     *         ),
     *     ),
     * )
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function authenticate(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->badRequest(__('messages.error.unauthorized'));
            }
        } catch (JWTException $e) {
            Log::error($e->getMessage() . ' on file ' . $e->getFile() . ' on line ' . $e->getLine());
            return $this->createApiResponseErrors(__('messages.error.unavailable'), Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return $this->createApiResponse(['token' => $token]);
    }

    /**
     * @OA\Get(path="/refresh-token", tags={"Auth"}, security={ {"bearer": {}} },
     *     @OA\Response(response="200", description="Success",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(@OA\Property(property="token", type="string"))
     *         ),
     *     ),
     *     @OA\Response(response="422", description="Unprocessable Entity",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(@OA\Property(property="errors", type="array", @OA\Items(type="string")))
     *         ),
     *     ),
     * )
     *
     * @return JsonResponse
     */
    public function refreshToken()
    {
        $token = JWTAuth::getToken();
        if (!$token) {
            return $this->badRequest(__('messages.error.unauthorized'));
        }

        try {
            $token = JWTAuth::refresh($token);
        } catch (TokenInvalidException $e) {
            return $this->badRequest(__('messages.error.unauthorized'));
        }

        return $this->createApiResponse(['token' => $token]);
    }

    /**
     * @OA\Post(path="/forgot-password", tags={"Auth"},
     *     @OA\RequestBody(required=true,
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(type="object",
     *                 @OA\Property(property="email", type="string"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(response="200", description="Success",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(@OA\Property(property="token", type="string"))
     *         ),
     *     ),
     *     @OA\Response(response="422", description="Unprocessable Entity",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(@OA\Property(property="errors", type="array", @OA\Items(type="string")))
     *         ),
     *     ),
     * )
     *
     * @param ForgotPasswordRequest $request
     * @return JsonResponse
     * @throws NotFoundException
     * @throws ServiceUnavailableException
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        /** @var User $user */
        $user = User::where('email', $request->input('email'))->first();
        if (null === $user) {
            throw new NotFoundException(__('messages.error.notfound'));
        }

        $response = $this->broker()->sendResetLink($request->only('email'));
        if ($response !== Password::RESET_LINK_SENT) {
            throw new ServiceUnavailableException(__('messages.error.unavailable'));
        }

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Put(path="/reset-password", tags={"Auth"},
     *     @OA\RequestBody(required=true,
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(type="object",
     *                 @OA\Property(property="token", type="string"),
     *                 @OA\Property(property="newPassword", type="string"),
     *                 @OA\Property(property="passwordConfirmation", type="string"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(response="200", description="Success",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(@OA\Property(property="token", type="string"))
     *         ),
     *     ),
     *     @OA\Response(response="422", description="Unprocessable Entity",
     *         @OA\MediaType(mediaType="application/json",
     *             @OA\Schema(@OA\Property(property="errors", type="array", @OA\Items(type="string")))
     *         ),
     *     ),
     * )
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     * @throws ConflictException
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $resetPassword = DB::table('password_resets')->where('token', $request->input('token'))->first();
        if (null === $resetPassword || Carbon::now() > Carbon::parse($resetPassword->created_at)->addHour()) {
            throw new ConflictException(__('messages.users.invalid_token'));
        }

        $user = User::where('email', $resetPassword->email)->first();
        $data['password'] = bcrypt($request->input('newPassword'));
        $user->fill($data)->save();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
