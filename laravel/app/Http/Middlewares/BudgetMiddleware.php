<?php

namespace App\Http\Middlewares;

use App\Models\Budget;


class BudgetMiddleware extends BaseMiddleware {
    public static string $model = Budget::class;
}
