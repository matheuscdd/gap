<?php

namespace App\Http\Middlewares;

use App\Models\User;


class UserMiddleware extends BaseMiddleware {
    public static string $model = User::class;
}
