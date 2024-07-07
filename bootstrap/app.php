<?php

use App\Exceptions\AppError;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function(Exception $error, Request $request) {
            if ($error instanceof ValidationException) {
                return response()->json([
                    'msg' => 'Ocorreu um erro ao validar os seguintes campos',
                    'errors' => $error->validator->errors()
                ], 422);
            }


            if ($error instanceof AppError) {
                return response()->json([
                    'msg' => $error->getMessage()
                ], $error->getCode());
            }

            if ($request->is('api/*') && $error instanceof NotFoundHttpException) {
                return response()->json([
                    'msg' => 'Não encontrado'
                ], 404);
            }

            if ($error instanceof AccessDeniedHttpException) {
                return response()->json([
                    'msg' => 'Não autorizado'
                ], 403);
            }

            Log::error('Internal', [$error]);
            
            return response()->json([
                'msg' => 'Ocorreu um erro interno no servidor'
            ], 500);
        });
    })->create();
