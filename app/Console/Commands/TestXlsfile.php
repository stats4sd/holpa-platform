<?php

namespace App\Console\Commands;

use App\Models\XlsformTemplate;
use Illuminate\Console\Command;

class TestXlsfile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-xlsfile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get xlsfile of a xlsform template.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $xlsformTemplate = XlsformTemplate::find(1);

        ray($xlsformTemplate);

        $xlsfiles = $xlsformTemplate->getMedia('xlsform_file');

        ray($xlsfiles);

        ray($xlsfiles[0]);

        ray($xlsfiles[0]->getPath());
    }
}
