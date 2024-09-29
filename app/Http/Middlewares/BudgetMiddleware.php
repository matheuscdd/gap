<?php

namespace App\Http\Middlewares;

use App\Exceptions\AppError;
use App\Models\Budget;
use Closure;
use Illuminate\Http\Request;

class BudgetMiddleware {
    public function handle(Request $request, Closure $next) {
        $id = $request->route('id');
        $budget = Budget::find($id);
        if ($id && !boolval($budget)) {
            throw new AppError('Não encontrado', 404);
        }
        return $next($request);
    }
}
