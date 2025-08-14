<?php

use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role'                 => RoleMiddleware::class,
            'permission'           => PermissionMiddleware::class,
            'role_or_permission'   => RoleOrPermissionMiddleware::class,
        ]);

        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Only render JSON for API routes
        $exceptions->shouldRenderJsonWhen(
            fn(Request $req, Throwable $e): bool => $req->is('api/*')
        );

        // AuthenticationException: JSON on API, otherwise fall back to default HTML
        $exceptions->render(function (AuthenticationException $e, Request $req) {
            if ($req->is('api/*')) {
                return response()->json([
                    'error_message' => 'Unauthorized',
                ], 401);
            }
        });

        // UnauthorizedException: JSON on API, otherwise fall back to default HTML
        $exceptions->render(function (UnauthorizedException $e, Request $req) {
            if ($req->is('api/*')) {
                return response()->json([
                    'error_message' => 'Unauthorized Role',
                ], 401);
            }
        });
    })->create();
