<?php

use Illuminate\Support\Facades\Artisan;

use App\Jobs\FetchFeedJob;

class FetchFeedTest extends TestCase
{
    public function testConsole()
    {
        app('Illuminate\Contracts\Bus\Dispatcher');
        $this->expectsJobs(FetchFeedJob::class);

        $return = Artisan::call('command:fetch-feed', ['feed' => 'file://' . __DIR__ . '/data/rss-feed.xml']);
        $this->assertEquals(0, $return);
    }

    public function testJob()
    {
        $job = new FetchFeedJob('file://' . __DIR__ . '/data/rss-feed.xml');
        $json = $job->handle();

        $reference = json_decode(file_get_contents(__DIR__ . '/data/rss-feed.json'), true);

        $this->assertSame($json, $reference);
    }
}
