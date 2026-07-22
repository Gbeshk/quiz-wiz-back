<?php

use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Request;
use Illuminate\Routing\Exceptions\InvalidSignatureException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();

        $middleware->api(prepend: [
            HandleCors::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*'),
        );

        $exceptions->render(function (InvalidSignatureException $e, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            if ($request->routeIs('verification.verify')) {
                $user = User::find($request->route('id'));

                if ($user && $user->hasVerifiedEmail()) {
                    return response()->json([
                        'status' => 'already_verified',
                        'message' => 'Email already verified.',
                    ]);
                }

                return response()->json([
                    'status' => 'expired',
                    'email' => $user?->email,
                    'message' => 'Verification link is invalid or has expired.',
                ], 400);
            }

            return response()->json([
                'status' => 'invalid_signature',
                'message' => 'This link is invalid or has expired.',
            ], 400);
        });
    })->create();
