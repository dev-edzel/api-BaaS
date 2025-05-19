<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(UserRequest $request)
    {
        return $this->userService->index($request);
    }

    public function show(User $user)
    {
        return $this->userService->show($user);
    }

    public function store(UserRequest $request)
    {
        return $this->userService->store($request);
    }

    public function update(UserRequest $request, User $user)
    {
        return $this->userService->update($request, $user);
    }

    public function destroy(User $user)
    {
        return $this->userService->destroy($user);
    }

    public function trashed(UserRequest $request)
    {
        return $this->userService->trashed($request);
    }

    public function restore(string $id)
    {
        return $this->userService->restore($id);
    }
}
