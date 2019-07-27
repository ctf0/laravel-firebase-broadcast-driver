<?php

namespace ctf0\Firebase\Broadcasters;

use Illuminate\Support\Str;
use Kreait\Firebase\ServiceAccount;
use Morrislaptop\Firestore\Factory;
use Kreait\Firebase\Exception\ApiException;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Broadcasting\Broadcasters\Broadcaster;

class FSDB extends Broadcaster
{
    protected $db;
    protected $config;

    /**
     * Create a new broadcaster instance.
     */
    public function __construct()
    {
        $this->config = config('broadcasting.connections.firebase');

        $sr_account = ServiceAccount::fromJsonFile(base_path($this->config['creds_file']));

        $this->db = (new Factory())
                        ->withServiceAccount($sr_account)
                        ->createFirestore();
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
        $db = $this->db;

        try {
            $coll = $db->collection($this->config['collection_name']);
            $doc  = $coll->document(md5(Str::uuid()));
            $doc->set([
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
