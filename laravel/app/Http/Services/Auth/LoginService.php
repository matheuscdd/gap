<?php

namespace App\Http\Services\Auth;

use App\Exceptions\AppError;

class LoginService {
    public static function execute(array $data) {
        if ((!$token = auth()->attempt($data)) || (!auth()->user()->active)) {
            throw new AppError('Email ou senha incorretos', 401);
        }

        return response(['token' => $token], 201);
    }
}
