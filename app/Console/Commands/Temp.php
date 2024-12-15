<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Temp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:temp';

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
        (new \Stats4sd\FilamentOdkLink\Services\OdkLinkService('https://odk-test.stats4sdtest.online/v1'))
            ->createCsvLookupFile(
                xlsform: \App\Models\Xlsforms\Xlsform::find(3),
                requiredMedia: \Stats4sd\FilamentOdkLink\Models\OdkLink\RequiredMedia::find(9));
    }
}
