<?php

namespace App\Http\Middlewares;

use App\Exceptions\AppError;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class UserMiddleware {
    public function handle(Request $request, Closure $next) {
        $id = $request->route('id');
        $user = User::find($id);
        if ($id && (!boolval($user) || !$user->active)) {
            throw new AppError('Não encontrado', 404);
        }
        return $next($request);
    }
}
