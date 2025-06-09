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
    protected $signature = 'app:test';

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
            Locale::find(18),
            XlsformTemplate::find(1),
            User::find(2),
        );
    }
}
