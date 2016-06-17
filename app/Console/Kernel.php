<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\FetchFeed::class,
        Commands\PlaySonosSongConsole::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('command:fetch-feed http://thenextweb.com/feed/')
            ->everyFiveMinutes()
            ->sendOutputTo(storage_path('feeds/tnw.json'));

        // Lunch tune
        $schedule->command('command:play-sonos-song "spotify:track:38A8PsjsoOq7g49uaGZ1k4"')
            ->cron('0,30 12 * * 1-5')
            ->timezone('Europe/Amsterdam');
    }
}
