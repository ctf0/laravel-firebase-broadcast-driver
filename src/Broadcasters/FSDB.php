<?php

namespace ctf0\Firebase\Broadcasters;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Kreait\Firebase\ServiceAccount;
use Morrislaptop\Firestore\Factory;
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
     */
    public function __construct($config)
    {
        $sr_account   = ServiceAccount::fromJsonFile(base_path($config['creds_file']));
        $this->config = $config;
        $this->db     = (new Factory())
                        ->withServiceAccount($sr_account)
                        ->createFirestore();
    }

    /**
     * {@inheritdoc}
     */
    public function broadcast(array $channels, $event, array $payload = [])
    {
        $db     = $this->db;
        $socket = Arr::pull($payload, 'socket');

        foreach ($this->formatChannels($channels) as $channel) {
            try {
                $coll = $db->collection($this->config['collection_name']);
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
