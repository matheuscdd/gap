<?php

namespace App\Http\Middlewares;

use App\Models\Truck;


class TruckMiddleware extends BaseMiddleware {
    public static string $model = Truck::class;
}
