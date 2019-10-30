<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use JWTAuth;
use Log;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthController extends Controller
{
    /**
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
}
