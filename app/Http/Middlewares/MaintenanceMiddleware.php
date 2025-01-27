<?php

namespace App\Http\Middlewares;

use App\Models\Maintenance;


class MaintenanceMiddleware extends BaseMiddleware {
    public static string $model = Maintenance::class;
}
