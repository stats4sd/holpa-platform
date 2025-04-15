<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Xlsform;

class TestCommand extends Command
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
    protected $description = 'For running test or one-time commands that do not need to be kept during development';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $xlsform = Xlsform::find(1);

        $xlsform->generateXlsfile();
    }
}
