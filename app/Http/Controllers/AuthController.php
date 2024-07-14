<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\{LoginRequest, RefreshRequest};
use App\Services\Auth\{RefreshService, LoginService};

class AuthController extends Controller {
    public function login(LoginRequest $request) {
        $loginService = new LoginService();
        return $loginService->execute($request->validated());
    }

    public function refresh(RefreshRequest $request) {
        $refreshService = new RefreshService();
        return $refreshService->execute($request->validated());
    }
}
