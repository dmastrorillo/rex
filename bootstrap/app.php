<?php

use App\Exceptions\APIException;
use App\Http\Middleware\AddTraceIdForApiRequests;
use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->encryptCookies(except: ['appearance']);

        $middleware->web(append: [
            AddTraceIdForApiRequests::class,
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,

        ]);

        $middleware->api(append: [
            AddTraceIdForApiRequests::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException | MethodNotAllowedHttpException $e, Request $request) {
            if($request->is('api/*')) {
                if($e instanceof MethodNotAllowedHttpException) {
                    return APIException::createMethodNotAllowed($request)->render();
                }

                if($e instanceof NotFoundHttpException) {
                    return APIException::createRouteNotFound($request)->render();
                }
            }
        });
    })->create();
