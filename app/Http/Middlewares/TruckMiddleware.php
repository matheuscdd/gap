<?php

namespace App\Http\Middlewares;

use App\Exceptions\AppError;
use App\Models\Truck;
use Closure;
use Illuminate\Http\Request;

# TODO - abstrair em uma função para todos os casos
class TruckMiddleware {
    public function handle(Request $request, Closure $next) {
        $id = $request->route('id');
        $truck = Truck::find($id);
        if ($id && !boolval($truck)) {
            throw new AppError('Não encontrado', 404);
        }
        return $next($request);
    }
}
