<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserInfoResource;
use App\Models\UserInfo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserInfoService extends Controller
{
    public function update($request, UserInfo $userInfo): JsonResponse
    {
        $changes = DB::transaction(function () use ($request, $userInfo) {
            $changes = $this->resourceParser($request, $userInfo);

            if ($request->hasFile('avatar')) {
                $avatarPath = $this->uploadImage($request->file('avatar'), 'images');
                if ($avatarPath) {
                    $changes['avatar'] = $avatarPath;
                    $userInfo->update(['avatar' => $avatarPath]);
                }
            }

            if ($changes) {
                if ($log = $this->log('UPDATE USER INFO', $changes)) {
                    $userInfo->update(['last_modified_log_id' => $log->id]);
                }
            }

            return $changes;
        });

        return response()->success(
            $changes ? 'Updating User Info Successful' : 'No changes made.',
            new UserInfoResource($userInfo)
        );
    }
}
