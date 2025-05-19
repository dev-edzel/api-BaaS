<?php

namespace App\Services\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Jobs\ProcessResetPasswordLink;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthService extends Controller
{
    public function registerUser($request): JsonResponse
    {
        $validated = $request->validated();
        $user = DB::transaction(function () use ($validated, $request) {
            $user = $this->createUser($validated);
            $this->createUserInfo($user->id, $validated['userInfo'], $request);

            return $user;
        });

        return response()->success(
            'User Registration Successful',
            new UserResource($user->load('userInfo')),
            201
        );
    }

    public function loginUser($request): JsonResponse
    {
        $request->validated();

        $user = User::where('email', $request->login)
            ->orWhere('username', $request->login)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['The provided credentials are incorrect.'],
            ]);
        }
        if ($user->is_active !== 1) {
            throw ValidationException::withMessages([
                'login' => ['Your account is inactive. Please contact support.'],
            ]);
        }

        return response()->json([
            'status' => 0,
            'message' => "Welcome back, {$user->username}! You have successfully logged in.",
            'data' => new UserResource($user),
            'bearer_token' => $user->createToken()->toString(),
            'expires_in' => config('jwt.ttl') * 60,
        ]);
    }

    public function logoutUser(Request $request): JsonResponse
    {
        try {
            $tokenString = $request->bearerToken();

            if (!$tokenString) {
                return response()->failed('No token provided');
            }

            $user = auth()->user();

            if (!$user) {
                return response()->failed('User not authenticated', 401);
            }

            $user->destroyToken($tokenString);

            return response()->success(
                "Goodbye, {$user->username}! You have successfully logged out.",
                new UserResource($user)
            );
        } catch (\Exception $e) {
            return response()->failed($e->getMessage(), 400);
        }
    }

    public function refreshToken($request): JsonResponse
    {
        try {
            $oldTokenString = $request->bearerToken();

            if (!$oldTokenString) {
                return response()->failed('No token provided');
            }

            $newToken = auth()->user()->refreshToken($oldTokenString);

            return response()->json([
                'status' => 0,
                'message' => "Token refreshed successfully",
                'refreshed_token' => $newToken->toString(),
                'expires_in' => config('jwt.ttl') * 60,
            ]);
        } catch (\Exception $e) {
            return response()->failed($e->getMessage());

        }
    }

    public function userProfile(): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->failed('User not authenticated');
        }

        return response()->success(
            'User details retrieved successfully.',
            new UserResource($user)
        );
    }

    public function sendPasswordResetLink($request)
    {
        $request->validate([
            'email' => ['required, email, exists:users,email']
        ]);

        $user = User::where('email', $request->email)->first();
        $token = Str::random(60);
        $expiresAt = now()->addMinutes(15);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
                'expires_at' => $expiresAt
            ]
        );

        $resetUrl = config('resetpassword.link') . '?token=' . $token . '&email=' . urlencode($user->email);

        $mailData = [
            'email' => $user->email,
            'resetUrl' => $resetUrl,
        ];

        ProcessResetPasswordLink::dispatch($mailData);

        return response()->success('Sending Password Reset Link', $resetUrl);
    }

    public function resetPassword($request)
    {
        $request->validate([
            'email' => ['required, email, exists:users,email'],
            'token' => ['required'],
            'new_password' => ['required, min:6, confirmed'],
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$reset) {
            return response()->failed('Invalid token.');
        }

        if (now()->greaterThan($reset->expires_at)) {
            return response()->failed('Token has expired. Please request a new one.');
        }

        if (!Hash::check($request->token, $reset->token)) {
            return response()->failed('Invalid token.');
        }

        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->new_password)]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->success('Password successfully changed.');
    }

    protected function createUser($validated)
    {
        return User::create($validated);
    }

    protected function createUserInfo($userId, array $userInfo, $request): void
    {
        $avatar = $this->uploadImage(
            $request->file('userInfo.avatar'),
            'images'
        );

        UserInfo::create(array_merge($userInfo, [
            'user_id' => $userId,
            'avatar' => $avatar,
        ]));
    }
}
