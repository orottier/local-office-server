<?php

namespace App\Jobs;

use Log;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\TransferException;

class SendSlackMessage extends Job
{
    protected $recipient, $message;

    public function __construct($recipient, $message, Client $client = null)
    {
        $this->recipient = $recipient;
        $this->message = $message;

        if (!$client) {
            $client = new Client([
                'base_uri' => 'https://hooks.slack.com',
                'timeout'  => 3.0,
            ]);
        }
        $this->client = $client;
    }

    public function handle()
    {
        try {
            $response = $this->client->request('POST', 'https://hooks.slack.com/services/' . env('SLACK_ENDPOINT'), [
                'json' => [
                    'channel' => $this->recipient,
                    'text' => $this->message,
                    'username' => 'OfficeBot',
                    'icon_emoji' => ':ghost:',
                ],
            ]);
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
