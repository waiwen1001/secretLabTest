<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
      $exceptions->render(function (NotFoundHttpException $exception, Request $request) {
        // API route not found
        return response()->json(['error' => "API route not found"], 404);
      });

      $exceptions->render(function (MethodNotAllowedHttpException $exception, Request $request) {
        // API method not allowed
        return response()->json(['error' => "Method Not Allowed"], 405);
      });

    })->create();
