<?php

namespace App\Http\Middlewares;

use App\Exceptions\AppError;
use App\Models\StockType;
use Closure;
use Illuminate\Http\Request;

class StockTypeMiddleware {
    public function handle(Request $request, Closure $next) {
        $id = $request->route('id');
        $stockType = StockType::find($id);
        if ($id && !boolval($stockType)) {
            throw new AppError('Não encontrado', 404);
        }
        return $next($request);
    }
}
