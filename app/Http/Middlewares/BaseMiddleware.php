<?php

namespace App\Http\Middlewares;

use App\Exceptions\AppError;
use App\Models\Driver;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;


class BaseMiddleware {
    public static string $model;

    public function handle(Request $request, Closure $next) {
        $id = $request->route('id');
        $instance = $this->getModel()::find($id);
        if ($id && !boolval($instance)) {
            throw new AppError('Não encontrado', 404);
        }
        return $next($request);
    }

    private function getModel(): Model {
        return new static::$model;
    }
}
