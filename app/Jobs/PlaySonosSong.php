<?php

namespace App\Jobs;

use GuzzleHttp\Exception\TransferException;
use Log;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;

class PlaySonosSong extends QueuedJob
{
    protected $song, $client;

    public function __construct($song, $client = null)
    {
        $this->song = $song;
        $this->client = $client;
    }

    public function handle()
    {
        if (!$this->client) {
            $this->client = new Client([
                'base_uri' => env('SONOS_HOST'),
                'timeout'  => 3.0,
            ]);
        }

        try {
            $response = $this->client->request('GET', '/' . env('SONOS_ROOM') . '/spotify/now/' . $this->song);
        } catch (TransferException $e) {
            Log::error(Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                Log::error(Psr7\str($e->getResponse()));
            }
            return false;
        }

        return true;
    }
}
