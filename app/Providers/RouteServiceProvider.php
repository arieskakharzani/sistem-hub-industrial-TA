<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

        // Redirect berdasarkan role
        $this->app->singleton('login.redirect', function ($app) {
            return function ($user) {
                switch ($user->active_role) {
                    case 'pelapor':
                        // return route('pelapor.dashboard');
                        return route('dashboard');
                    case 'terlapor':
                        return route('terlapor.dashboard');
                    case 'mediator':
                        return route('mediator.dashboard');
                    case 'kepala_dinas':
                        return route('kepala_dinas.dashboard');
                    default:
                        return '/';
                }
            };
        });
    }
}
