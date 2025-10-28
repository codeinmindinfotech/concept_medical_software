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
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'check.guard.role' => \App\Http\Middleware\CheckGuardRole::class,
            'auth.multi' => \App\Http\Middleware\MultiGuardAuthenticate::class,
        ]);
        // ✅ 2. Register default Laravel web middleware stack
        $middleware->web(append: [
            \App\Http\Middleware\VerifyCsrfToken::class, // 👈 CSRF Middleware
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
