<?php

namespace App\Http\Services\Auth;

use App\Exceptions\AppError;

class RefreshService {
    public function put() {
        if (!$token = auth()->refresh()) {
            throw new AppError('Token incorreto', 401);
        }

        return response()->json([
            'token' => $token
        ]);
    }
}
