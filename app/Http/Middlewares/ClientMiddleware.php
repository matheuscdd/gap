<?php

namespace App\Http\Middlewares;

use App\Models\Client;


class ClientMiddleware extends BaseMiddleware {
    public static string $model = Client::class;
}
