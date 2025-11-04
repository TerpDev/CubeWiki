<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(Login::class, function ($event) {
            $user = $event->user;

            $user->tokens()->delete();

            $token = $user->createToken('auth-token')->plainTextToken;

            session()->put('api_token', $token);
        });

        Event::listen(Registered::class, function ($event) {
            $user = $event->user;

            $token = $user->createToken('auth-token')->plainTextToken;

            session()->put('api_token', $token);
        });
    }
}
