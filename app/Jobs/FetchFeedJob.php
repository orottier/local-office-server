<?php

namespace App\Jobs;

use Vinelab\Rss\Rss;

class FetchFeedJob extends Job
{
    protected $feed;

    public function __construct($feed)
    {
        $this->feed = $feed;
    }

    public function handle()
    {
        $rss = new Rss();
        $feed = $rss->feed($this->feed);
        $info = $feed->info();

        $info['articles'] = $feed->articles()->map(function ($article) {
            return [
                'title' => $article->title,
                'description' => $article->description,
                'pubDate' => $article->pubDate,
                'link' => $article->link,
            ];
        })->all();

        return $info;
    }
}
