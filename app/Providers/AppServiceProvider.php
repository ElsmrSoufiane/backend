<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    // app/Providers/AppServiceProvider.php
public function register()
{
   
}

public function boot()
{
    // Enable SQL query logging
    if (env('APP_DEBUG')) {
        \DB::listen(function($query) {
            \Log::info("SQL: {$query->sql}", $query->bindings);
        });
    }
}
}
