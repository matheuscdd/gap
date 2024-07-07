<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\LoginService;

class AuthController extends Controller {
    public function login(LoginRequest $request) {
        $loginService = new LoginService();
        return $loginService->execute($request->validated());
    }
}
