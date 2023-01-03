<?php

namespace ctf0\Firebase\Broadcasters;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\ApiException;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Broadcasting\Broadcasters\Broadcaster;

class FSDB extends Broadcaster
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
                            ->withServiceAccount($config['creds_file'])
                            ->createDatabase();
    }

    /**
     * {@inheritdoc}
     */
    public function broadcast(array $channels, $event, array $payload = [])
    {
        $db     = $this->db;
        $coll   = $db->collection($this->config['collection_name']);
        $socket = Arr::pull($payload, 'socket');

        foreach ($this->formatChannels($channels) as $channel) {
            try {
                $doc  = $coll->document(md5(Str::uuid()));
                $doc->set([
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
