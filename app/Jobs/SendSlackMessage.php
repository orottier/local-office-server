<?php

namespace App\Jobs;

use Log;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

class SendSlackMessage extends Job
{
    protected $recipient, $message;

    public function __construct($recipient, $message, Client $client = null)
    {
        $this->recipient = $recipient;
        $this->message = $message;

        if (!$client) {
            $client = new Client([
                // Base URI is used with relative requests
                'base_uri' => 'https://hooks.slack.com',
                // You can set any number of default request options.
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
        } catch (RequestException $e) {
            Log::error(Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                Log::error(Psr7\str($e->getResponse()));
            }
            return false;
        } catch (ClientException $e) {
            Log::error(Psr7\str($e->getRequest()));
            Log::error(Psr7\str($e->getResponse()));
            return false;
        }

        return true;
    }
}
