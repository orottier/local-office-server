<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Middleware;

use App\Jobs\SendSlackMessage;

class SendSlackMessageTest extends TestCase
{
    use DatabaseMigrations;

    private function getClient($response)
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([$response]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        return $client;
    }

    public function testSendSlackMessageOk()
    {
        $client = $this->getClient(new Response(200, ['X-Foo' => 'Bar']));
        $job = new SendSlackMessage("@otto", "hello", $client);
        $this->assertTrue($job->handle());
    }

    public function testSendSlackMessageFail()
    {
        $client = $this->getClient(
            new RequestException("Error Communicating with Server", new Request('GET', 'test'))
        );
        $job = new SendSlackMessage("@otto", "hello", $client);
        $this->assertFalse($job->handle());
    }

    public function testSendSlackMessageDataPosted()
    {
        $container = [];
        $history = Middleware::history($container);

        $mock = new MockHandler([new Response(200, ['X-Foo' => 'Bar'])]);
        $stack = HandlerStack::create($mock);
        // Add the history middleware to the handler stack.
        $stack->push($history);

        $client = new Client(['handler' => $stack]);

        $job = new SendSlackMessage("@otto", "hello", $client);
        $job->handle();

        // Count the number of transactions
        $this->assertEquals(1, count($container));

        // Iterate over the requests and responses
        foreach ($container as $transaction) {
            $this->assertEquals('POST', $transaction['request']->getMethod());
            $data = $transaction['request']->getBody()->read(1000);
            $this->assertNotEmpty($data);

            $json = json_decode($data, true);
            $this->assertNotEmpty($json);

            $this->assertContains('@otto', $json);
            $this->assertContains('hello', $json);
        }
    }
}
