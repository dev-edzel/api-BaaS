<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Models\UserInfo;
use Illuminate\Http\JsonResponse;

class UserInfoService extends Controller
{
    public function update($request, UserInfo $userInfo): JsonResponse
    {
    }
}
