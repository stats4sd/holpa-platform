<?php

namespace App\Console\Commands;

use App\Models\XlsformTemplates\XlsformTemplate;
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
       $xlsformTemplate = XlsformTemplate::first();

       dd($xlsformTemplate->surveyRows->count());

    }
}
