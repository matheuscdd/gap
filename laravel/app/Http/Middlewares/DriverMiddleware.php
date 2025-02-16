<?php

namespace App\Http\Middlewares;

use App\Models\Driver;


class DriverMiddleware extends BaseMiddleware {
    public static string $model = Driver::class;
}
