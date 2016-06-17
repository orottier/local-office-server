<?php

use Illuminate\Support\Facades\Artisan;

use App\Jobs\PlaySonosSong;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Middleware;

class PlaySonosSongTest extends TestCase
{
    private function getClient($response)
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([$response]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        return $client;
    }

    public function testConsole()
    {
        app('Illuminate\Contracts\Bus\Dispatcher');
        $this->expectsJobs(PlaySonosSong::class);

        $return = Artisan::call('command:play-sonos-song', ['song' => '<song-id>']);
        $this->assertEquals(0, $return);
    }

    public function testPlaySongJobOK()
    {
        $client = $this->getClient(new Response(200));
        $job = new PlaySonosSong('<song-id>', $client);
        $this->assertTrue($job->handle());
    }

    public function testPlaySongJobFail()
    {
        $client = $this->getClient(
            new RequestException('Error Communicating with Server', new Request('GET', 'test'))
        );
        $job = new PlaySonosSong('<song-id>', $client);
        $this->assertFalse($job->handle());
    }

    public function testPlaySongJobDataPosted()
    {
        $container = [];
        $history = Middleware::history($container);

        $mock = new MockHandler([new Response(200, ['X-Foo' => 'Bar'])]);
        $stack = HandlerStack::create($mock);
        // Add the history middleware to the handler stack.
        $stack->push($history);

        $client = new Client(['handler' => $stack]);

        $job = new PlaySonosSong('<song-id>', $client);
        $job->handle();

        // Count the number of transactions
        $this->assertEquals(1, count($container));

        // Iterate over the requests and responses
        foreach ($container as $transaction) {
            $this->assertEquals('GET', $transaction['request']->getMethod());
            $this->assertContains(urlencode('<song-id>'), $transaction['request']->getUri()->getPath());
            $data = $transaction['request']->getBody()->read(1000);
            $this->assertEmpty($data);
        }
    }
}
