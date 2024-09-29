<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\{LoginRequest, RefreshRequest};
use App\Http\Services\Auth\{LoginService, RefreshService};

class AuthController extends Controller {
    public function login(LoginRequest $request) {
        return LoginService::execute($request->validated());
    }

    public function refresh(RefreshRequest $request) {
        return RefreshService::put($request->validated());
    }
}
