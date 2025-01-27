<?php

namespace App\Http\Middlewares;

use App\Models\Delivery;


class DeliveryMiddleware extends BaseMiddleware {
    public static string $model = Delivery::class;
}
