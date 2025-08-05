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
            'guest.redirect' => \App\Http\Middleware\RedirectIfAuthenticatedCustom::class,
            'role' => Spatie\Permission\Middleware\RoleMiddleware::class,
            'status-akun' => \App\Http\Middleware\cekStatusAkun::class,
            'status-bimbingan-skripsi' => \App\Http\Middleware\cekStatusBimbinganSkripsi::class,
            'status-bimbingan-proposal' => \App\Http\Middleware\cekStatusBimbinganProposal::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
