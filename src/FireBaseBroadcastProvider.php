<?php

namespace ctf0\Firebase;

use ctf0\Firebase\Broadcasters\FSDB;
use ctf0\Firebase\Broadcasters\RTDB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Broadcasting\BroadcastManager;

class FireBaseBroadcastProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        app(BroadcastManager::class)->extend('firebase', function ($app) {
            $config = config('broadcasting.connections.firebase');

            return $config['type'] == 'database' ? new RTDB($config) : new FSDB($config);
        });
    }

    public function register()
    {
    }
}
