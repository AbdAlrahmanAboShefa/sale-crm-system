<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'set.locale' => \App\Http\Middleware\SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            $seconds = $e->getHeaders()['Retry-After'] ?? 60;
            $message = __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                ], 429);
            }

            if ($request->routeIs('login')) {
                return redirect()->route('login')->withErrors(['email' => $message])->withInput();
            }

            return back()->with('error', $message)->withInput();
        });
    })->create();
