<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Requests\Authentication\OTPRequest;
use App\Http\Requests\Authentication\PasswordResetRequest;
use App\Http\Requests\Authentication\RegisterRequest;
use App\Services\Authentication\AuthService;
use Illuminate\Http\Request;

class AuthController
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        return $this->authService->registerUser($request);
    }

    public function login(LoginRequest $request)
    {
        return $this->authService->loginUser($request);
    }

    public function verify(OTPRequest $request)
    {
        return $this->authService->verifyUser($request);
    }

    public function logout(Request $request)
    {
        return $this->authService->logoutUser($request);
    }

    public function refresh(Request $request)
    {
        return $this->authService->refreshToken($request);
    }

    public function profile()
    {
        return $this->authService->userProfile();
    }

    public function passwordResetLink(Request $request)
    {
        return $this->authService->sendPasswordResetLink($request);
    }

    public function resetPassword(PasswordResetRequest $request)
    {
        return $this->authService->resetPassword($request);
    }
}
