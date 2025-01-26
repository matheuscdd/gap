<?php

namespace App\Http\Middlewares;

use App\Exceptions\AppError;
use App\Models\Maintenance;
use Closure;
use Illuminate\Http\Request;

# TODO - abstrair em uma função para todos os casos
class MaintenanceMiddleware {
    public function handle(Request $request, Closure $next) {
        $id = $request->route('id');
        $maintenance = Maintenance::find($id);
        if ($id && !boolval($maintenance)) {
            throw new AppError('Não encontrado', 404);
        }
        return $next($request);
    }
}
