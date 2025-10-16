<?php

namespace App\Providers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        // Define Gates for update and delete authorization
        Gate::define('update', function ($user, $model) {
            return $user->id === $model->user_id;
        });

        Gate::define('delete', function ($user, $model) {
            return $user->id === $model->user_id;
        });

        // Define a macro to log session state
        Session::macro('logDriver', function () {
            Log::debug('Session driver initialized', [
                'driver'     => Session::getDefaultDriver(),
                'is_started' => $this->isStarted(),
                'session_id' => $this->getId(),
                // 'has_errors' => $this->hasErrors(), // This method does not exist
            ]);
        });

        // Call the macro to log session info
        Session::logDriver();
    }
}
