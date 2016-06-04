<?php

namespace App\Jobs;

use Log;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

class SendSlackMessage extends Job
{
    protected $recipient, $message;

    public function __construct($recipient, $message)
    {
        $this->recipient = $recipient;
        $this->message = $message;
    }

    public function handle()
    {
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://hooks.slack.com',
            // You can set any number of default request options.
            'timeout'  => 3.0,
        ]);

        try {
            $response = $client->request('POST', 'https://hooks.slack.com/services/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', [
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
