<?php

namespace Jundayw\LaravelCacheGuard;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Jundayw\LaravelCacheGuard\CacheGuard;

class CacheGuardServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Auth::extend('cache', function($app, $name, $config) {
            $guard = new CacheGuard(
                $name,
                Auth::createUserProvider($config['provider'] ?? null),
                $app['session.store']
            );
            if (method_exists($guard, 'setCookieJar')) {
                $guard->setCookieJar($app['cookie']);
            }
            if (method_exists($guard, 'setDispatcher')) {
                $guard->setDispatcher($app['events']);
            }
            if (method_exists($guard, 'setRequest')) {
                $guard->setRequest($app->refresh('request', $guard, 'setRequest'));
            }
            return $guard;
        });
    }
}
