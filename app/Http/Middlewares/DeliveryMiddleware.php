<?php

namespace App\Http\Middlewares;

use App\Exceptions\AppError;
use App\Models\Delivery;
use Closure;
use Illuminate\Http\Request;

class DeliveryMiddleware {
    public function handle(Request $request, Closure $next) {
        $id = $request->route('id');
        $delivery = Delivery::find($id);
        if ($id && !boolval($delivery)) {
            throw new AppError('Não encontrado', 404);
        }
        return $next($request);
    }
}
