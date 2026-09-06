<?php

use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureAdministratorExists;
use App\Http\Middleware\EnsureSetupIsAvailable;
use App\Http\Middleware\SetLocale;
use App\Models\User;
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
        $middleware->web(append: [SetLocale::class]);
        $middleware->redirectGuestsTo(fn () => User::query()->where('is_admin', true)->exists()
            ? route('login')
            : route('setup'));
        $middleware->alias([
            'admin' => EnsureAdmin::class,
            'admin.exists' => EnsureAdministratorExists::class,
            'setup.available' => EnsureSetupIsAvailable::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {})->create();
