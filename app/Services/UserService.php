<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserService extends Controller
{
    public function index($request): JsonResponse
    {
        $users = User::search($request->search)
            ->orderBy('id', 'ASC')
            ->paginate($request->per_page ?? 10);

        return response()->success(
            'Successfully retrieved the list of users',
            UserResource::collection($users),
        );
    }

    public function show(User $user): JsonResponse
    {
    }

    public function store($request): JsonResponse
    {
    }

    public function update($request, User $user): JsonResponse
    {
    }

    public function destroy(User $user): JsonResponse
    {
    }

    public function trashed($request): JsonResponse
    {
    }

    public function restore(string $id): JsonResponse
    {

    }

    protected function createUser($validated)
    {
    }

    protected function createUserInfo($userId, array $userInfo, $request): void
    {
    }
}
