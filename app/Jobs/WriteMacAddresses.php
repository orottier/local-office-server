<?php

namespace App\Jobs;

use App\Models\User;

class WriteMacAddresses extends QueuedJob
{
    protected $outfile;

    public function __construct($outfile)
    {
        $this->outfile = $outfile;
    }

    public function handle()
    {
        $scratchFile = storage_path('users.scratch');
        $fp = fopen($scratchFile, 'w');
        User::with('macAddresses')->chunk(100, function ($users) use ($fp) {
            $users->each(function ($user) use ($fp) {
                $lines = $user->macAddresses->map(function ($address) use ($user) {
                    return $address->mac_address . ' ' . $user->username;
                });
                fwrite($fp, $lines->implode("\n") . "\n");
            });
        });
        fclose($fp);

        rename($scratchFile, $this->outfile);
    }
}
