<?php

namespace App\Http\Services\Auth;

use App\Exceptions\AppError;

class RefreshService {
    public static function put() {
        if (!$token = auth()->refresh()) {
            throw new AppError('Token incorreto', 401);
        }

        return response(['token' => $token], 201);
    }
}
