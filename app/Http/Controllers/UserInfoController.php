<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserInfoRequest;
use App\Models\UserInfo;
use App\Services\UserInfoService;
use Illuminate\Http\Request;

class UserInfoController extends Controller
{
    protected UserInfoService $userInfoService;

    public function __construct(UserInfoService $userInfoService)
    {
        $this->userInfoService = $userInfoService;
    }

    public function update(UserInfoRequest $request, UserInfo $userInfo)
    {
        return $this->userInfoService->update($request, $userInfo);
    }
}
