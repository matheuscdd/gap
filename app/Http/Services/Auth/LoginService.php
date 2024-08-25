<?php

namespace App\Http\Services\Auth;

use App\Exceptions\AppError;

class LoginService {
    public static function execute(array $data) {
        if (!$token = auth()->attempt($data)) {
            throw new AppError('Email ou senha incorretos', 401);
        }

        return response()->json([
            'token' => $token,
        ]);
    }
}
