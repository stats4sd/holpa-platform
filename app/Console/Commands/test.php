<?php

namespace App\Console\Commands;

use App\Events\NotifyUserThatLanguageImportIsComplete;
use App\Models\User;
use Illuminate\Console\Command;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformLanguages\Locale;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformTemplate;

class test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-notice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        NotifyUserThatLanguageImportIsComplete::dispatch(
            18,
            1,
            2
        );
    }
}
