<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'verified.user' => \App\Http\Middleware\VerifiedUserMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Upload too large. Use photos under 4 MB each and upload at most 8 at a time.'], 413);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['images' => 'Upload too large. Use photos under 4 MB each and upload at most 8 at a time.']);
        });
    })->create();
