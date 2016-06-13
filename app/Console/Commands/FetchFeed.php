<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Vinelab\Rss\Rss;

class FetchFeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fetch-feed {feed} {name}';

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
        $rss = new Rss();
        $feed = $rss->feed($this->argument('feed'));
        $info = $feed->info();

        $info['articles'] = $feed->articles()->map(function ($article) {
            return [
                'title' => $article->title,
                'description' => $article->description,
                'pubDate' => $article->pubDate,
                'link' => $article->link,
            ];
        });

        $baseDir = storage_path('feeds');
        if (!is_dir($baseDir)) {
            mkdir($baseDir, 0755, true);
        }

        file_put_contents($baseDir . '/' . $this->argument('name') . '.json', json_encode($info));
    }
}
