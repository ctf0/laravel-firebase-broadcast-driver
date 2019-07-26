<?php

namespace ctf0\Firebase;

use Illuminate\Support\Arr;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Exception\ApiException;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Broadcasting\Broadcasters\Broadcaster;

class FireBaseBroadcaster extends Broadcaster
{
    protected $db;
    protected $config;

    /**
     * Create a new broadcaster instance.
     */
    public function __construct()
    {
        $this->config = config('broadcasting.connections.firebase');

        $sr_account = ServiceAccount::fromJsonFile(base_path(Arr::get($this->config, 'creds_file')));

        $this->db = (new Factory())
                        ->withServiceAccount($sr_account)
                        ->withDatabaseUri(Arr::get($this->config, 'databaseURL'))
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
            $db->getReference(Arr::get($this->config, 'collection_name'))->push([
                'timestamp' => now()->timestamp,
                'channels'  => $this->formatChannels($channels),
                'data'      => $payload,
                'event'     => $event,
            ]);
        } catch (ApiException $e) {
            throw new BroadcastException($e);
        }
    }
}
