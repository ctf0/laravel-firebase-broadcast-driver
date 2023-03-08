<?php

namespace ctf0\Firebase\Broadcasters;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\ApiException;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Broadcasting\Broadcasters\Broadcaster;

class FCM extends Broadcaster
{
    use Common;

    protected $db;
    
    protected $config;

    /**
     * Create a new broadcaster instance.
     *
     * @param mixed $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Broadcasts the notification through the FCM
     */
    public function broadcast(array $channels, $event, array $payload = [])
    {
        foreach ($this->formatChannels($channels) as $channel) {
            try {
                $message = new Notification(
                    $notification->title,
                    $notification->description,
                );
                $res = (new Factory())
                    ->withServiceAccount($config['creds_file'])
                    ->withTarget('topic', $channel)
                    ->withNotification($message),
                    ->withData([
                        'channel'   => $channel,
                        'data'      => $payload,
                        'event'     => $event,
                        'socket'    => $socket,
                        'timestamp' => round(now()->valueOf()),
                    ]);
            } catch (ApiException $e) {
                throw new BroadcastException($e);
            }
        }
    }
}
