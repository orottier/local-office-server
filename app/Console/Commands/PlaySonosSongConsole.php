<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jobs\PlaySonosSong;

class PlaySonosSongConsole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:play-sonos-song {song}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Play a song on the SONOS system';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        dispatch(new PlaySonosSong($this->argument('song')));
    }
}
