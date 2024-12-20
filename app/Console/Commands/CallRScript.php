<?php

namespace App\Console\Commands;

use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Console\Command;

class CallRScript extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:call-r-script';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Call R script to calculate indicators';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Note:
        // I tried to install "stats4sd/laravel-r-setup" package with below command but failed:
        // composer require stats4sd/laravel-r-setup --dev
        //
        // It looks like this package does not support Laravel version 11 yet.
        // We will need to update it to support Laravel version 11 first.
        // I will switch to investigate how to get data from "properties" JSON column and add them to data export.

        $this->info('start');

        // TODO: call R script program

        $this->info('end');
    }
}
