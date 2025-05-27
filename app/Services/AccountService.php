<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AccountService
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

}
