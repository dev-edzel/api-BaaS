<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\AccountService;

class AccountController extends Controller
{
    protected AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function index(UserRequest $request)
    {
        return $this->accountService->index($request);
    }

}
