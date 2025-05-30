<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

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

    public function store($request): JsonResponse
    {
        $validated = $request->validated();
        $user = DB::transaction(function () use ($validated, $request) {
            $user = $this->createUser($validated);
            $this->createUserInfo($user->id, $validated['userInfo'], $request);

            return $user;
        });

        return response()->success(
            'Storing User Successful',
            new UserResource($user->load('userInfo')),
            201
        );
    }

    public function show(User $user): JsonResponse
    {
        return response()->success(
            'User retrieval was successful',
            new UserResource($user),
        );
    }

    public function update($request, User $user): JsonResponse
    {
        $changes = DB::transaction(function () use ($request, $user) {
            $changes = $this->resourceParser($request, $user);

            if ($changes) {
                if ($log = $this->log('UPDATE USER', $changes)) {
                    $user->update(['last_modified_log_id' => $log->id]);
                }
            }

            return $changes;
        });

        return response()->success(
            $changes ? 'Updating User Successful' : 'No changes made.',
            new UserResource($user)
        );
    }

    public function destroy(User $user): JsonResponse
    {
        DB::transaction(function () use ($user) {
            $log = $this->log('REMOVE USER', $user);
            $user->last_modified_log_id = $log->id;
            $user->save();
            $user->delete();
        });

        return response()->success(
            'User has been successfully deleted',
            new UserResource($user),
        );
    }

    public function trashed($request): JsonResponse
    {
        $users = User::search($request->search)
            ->orderBy('id', 'ASC')
            ->onlyTrashed()
            ->paginate(10);

        return response()->success(
            'Successfully retrieved list of deleted users',
            UserResource::collection($users)
        );
    }

    public function restore(string $id): JsonResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);

        DB::transaction(function () use ($user) {
            $log = $this->log('RESTORE USER', $user);
            $user->last_modified_log_id = $log->id;
            $user->save();
            $user->restore();
        });

        return response()->success(
            'Successfully restored user',
            new UserResource($user)
        );
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
