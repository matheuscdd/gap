<?php

namespace App\Http\Middlewares;

use App\Models\StockType;


class StockTypeMiddleware extends BaseMiddleware {
    public static string $model = StockType::class;
}
