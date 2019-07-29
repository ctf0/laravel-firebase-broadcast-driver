<h1 align="center">
    FireBase Broadcast Driver
    <br>
    <a href="https://packagist.org/packages/ctf0/firebase-broadcast-driver"><img src="https://img.shields.io/packagist/v/ctf0/firebase-broadcast-driver.svg" alt="Latest Stable Version" /></a> <a href="https://packagist.org/packages/ctf0/firebase-broadcast-driver"><img src="https://img.shields.io/packagist/dt/ctf0/firebase-broadcast-driver.svg" alt="Total Downloads" /></a>
</h1>

## Installation

- `composer require ctf0/firebase-broadcast-driver`

- the package internally use [`kreait/firebase-php`](https://firebase-php.readthedocs.io/en/latest/) to send data to firebase.

### Config
```php
// config/broadcasting

return [
    'connections' => [
        // ...

        'firebase' => [
            'driver' => 'firebase',
            'type' => 'firestore', // database or firestore
            'databaseURL' => env('FB_DB_URL'), // the real time database url
            'creds_file' => env('FB_CREDENTIALS_FILE'), // service account json file
            'collection_name' => env('FB_COLLECTION_NAME'), // ex.notifications
        ],
    ],
];
```

### Usage

- add `BROADCAST_DRIVER=firebase` to `.env`

- atm there no support for [laravel-echo](https://laravel.com/docs/5.8/broadcasting#installing-laravel-echo) "any help is appreciated" but no worries, you still get the same payload as other broadcast drivers.

    however you can check the [firebase api docs](https://firebase.google.com/docs/database/web/start) or [vuefire](https://github.com/vuejs/vuefire) if you are using `vue`, on how to listen for changes and update your app users accordingly.

#### Notification Data Sample
```json
{
    "notifications" : {
        "-LkgtAVVw0Ztwyjayd9n" : {
            "channels" : [ "private-App.User.091b0f7e-805b-4aab-8c99-445039157783" ],
            "data" : {
                "body" : "some body",
                "id" : "d54c44a2-8a42-43a4-bae0-e2b159d1533b",
                "title" : "some title",
                "type" : "App\\Notifications\\AlertUser"
            },
            "event" : "Illuminate\\Notifications\\Events\\BroadcastNotificationCreated",
            "timestamp": 1564183089538
        }
    }
}
```
