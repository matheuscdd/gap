<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\{LoginRequest, RefreshRequest};
use App\Http\Services\Auth\{LoginService, RefreshService};

class AuthController extends Controller {
    public function login(LoginRequest $request) {
        $loginService = new LoginService();
        return $loginService->execute($request->validated());
    }

    public function refresh(RefreshRequest $request) {
        $refreshService = new RefreshService();
        return $refreshService->put($request->validated());
    }
}
