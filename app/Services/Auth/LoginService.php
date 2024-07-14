<?php

namespace App\Services\Auth;

use App\Exceptions\AppError;

class LoginService {
    public function execute(array $data) {
        if (!$token = auth()->attempt($data)) {
            throw new AppError('Email ou senha incorretos', 401);
        }

        return response()->json([
            'token' => $token
        ]);
    }
}
