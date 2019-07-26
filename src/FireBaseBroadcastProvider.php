<?php

namespace ctf0\Firebase;

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
            return new FireBaseBroadcaster();
        });
    }

    public function register()
    {
    }
}
