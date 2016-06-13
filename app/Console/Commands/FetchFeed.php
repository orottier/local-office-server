<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jobs\FetchFeedJob;

class FetchFeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fetch-feed {feed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch an RSS feed and transform to JSON';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $job = new FetchFeedJob($this->argument('feed'));
        $data = dispatch($job);
        echo json_encode($data);
    }
}
