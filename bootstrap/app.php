<?php


// use App\Http\Middleware\SwitchCompanyDatabase;
// use Illuminate\Foundation\Application;
// use Illuminate\Foundation\Configuration\Exceptions;
// use Illuminate\Foundation\Configuration\Middleware;

// return Application::configure(basePath: dirname(__DIR__))
//     ->withRouting(
//         web: __DIR__.'/../routes/web.php',
//         commands: __DIR__.'/../routes/console.php',
//         health: '/up',
//     )
//     ->withMiddleware(function (Middleware $middleware): void {
//         $middleware->alias([
//             'switch.company.database' => SwitchCompanyDatabase::class,
//             'check.role' => \App\Http\Middleware\CheckRole::class,
//         ]);

//         // Set entire 'web' group middleware explicitly, placing your middleware before 'auth'
//         $middleware->appendToGroup('web', [
//             \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
//             \Illuminate\Session\Middleware\StartSession::class,
//             \Illuminate\View\Middleware\ShareErrorsFromSession::class,
//             \Illuminate\Routing\Middleware\SubstituteBindings::class,

//             // Your middleware BEFORE auth
//             'switch.company.database',

//             // Laravel auth middleware (use alias or class)
//             'auth',

//             // Any other middleware you want here...
//         ]);
//     })
//     ->withProviders([])
//     ->withExceptions(function (Exceptions $exceptions): void {
//         //
//     })
//     ->create();


use App\Http\Middleware\SwitchCompanyDatabase;
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
            'switch.company.database' => SwitchCompanyDatabase::class,
            'check.role' => \App\Http\Middleware\CheckRole::class,
        ]);
        // Make sure it runs in every request using 'web'appendToGroup
        // $middleware->appendToGroup('web', [
        //     'switch.company.database',
        // ]);
    })

    ->withProviders([

    ])
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
