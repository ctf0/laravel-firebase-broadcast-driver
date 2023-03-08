<?php

namespace ctf0\Firebase;

use ctf0\Firebase\Broadcasters\FSDB;
use ctf0\Firebase\Broadcasters\RTDB;
use ctf0\Firebase\Broadcasters\FCM;
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

            return match ($config['type']) {
                'database' => new RTDB($config),
                'messaging' => new FCM($config),
                'firestore' => new FSDB($config),
                default => new FSDB($config),
            };
        });
    }

    public function register()
    {
    }
}
