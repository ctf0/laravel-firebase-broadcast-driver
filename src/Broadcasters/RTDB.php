<?php

namespace ctf0\Firebase\Broadcasters;

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Exception\ApiException;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Broadcasting\Broadcasters\Broadcaster;

class RTDB extends Broadcaster
{
    protected $db;
    protected $config;

    /**
     * Create a new broadcaster instance.
     *
     * @param mixed $config
     */
    public function __construct($config)
    {
        $sr_account   = ServiceAccount::fromJsonFile(base_path($config['creds_file']));
        $this->config = $config;
        $this->db     = (new Factory())
                        ->withServiceAccount($sr_account)
                        ->withDatabaseUri($config['databaseURL'])
                        ->create();
    }

    /**
     * {@inheritdoc}
     */
    public function auth($request)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function validAuthenticationResponse($request, $result)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function broadcast(array $channels, $event, array $payload = [])
    {
        $db = $this->db->getDatabase();

        try {
            $db->getReference($this->config['collection_name'])
                ->push([
                    'timestamp' => round(now()->valueOf()), // return date == to js Date.now()
                    'channels'  => $this->formatChannels($channels),
                    'data'      => $payload,
                    'event'     => $event,
                ]);
        } catch (ApiException $e) {
            throw new BroadcastException($e);
        }
    }
}
