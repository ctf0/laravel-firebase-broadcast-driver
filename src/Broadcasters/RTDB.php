<?php

namespace ctf0\Firebase\Broadcasters;

use Illuminate\Support\Arr;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\ApiException;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Broadcasting\Broadcasters\Broadcaster;

class RTDB extends Broadcaster
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
        $this->db     = (new Factory())
                            ->withServiceAccount(base_path($config['creds_file']))
                            ->withDatabaseUri($config['databaseURL'])
                            ->createDatabase();
    }

    /**
     * {@inheritdoc}
     */
    public function broadcast(array $channels, $event, array $payload = [])
    {
        $db = $this->db->getReference($this->config['collection_name']);
        $socket = Arr::pull($payload, 'socket');

        foreach ($this->formatChannels($channels) as $channel) {
            try {
                $db->push([
                    'channel'   => $channel,
                    'data'      => $payload,
                    'event'     => $event,
                    'socket'    => $socket,
                    'timestamp' => round(now()->valueOf()), // return date == to js Date.now()
                ]);
            } catch (ApiException $e) {
                throw new BroadcastException($e);
            }
        }
    }
}
