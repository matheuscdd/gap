<?php

namespace App\Http\Middlewares;

use App\Exceptions\AppError;
use App\Models\Client;
use Closure;
use Illuminate\Http\Request;

class ClientMiddleware {
    public function handle(Request $request, Closure $next) {
        $id = $request->route('id');
        $client = Client::find($id);
        if ($id && !boolval($client)) {
            throw new AppError('Não encontrado', 404);
        }
        return $next($request);
    }
}