<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;
use App\Models\SurveyData\FishUse;
use App\Models\SurveyData\FarmSurveyData;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Submission;
use Stats4sd\FilamentOdkLink\Models\OdkLink\SurveyRow;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Xlsform;
use Stats4sd\FilamentOdkLink\Models\OdkLink\XlsformTemplate;
use Stats4sd\FilamentOdkLink\Services\OdkLinkService;

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
        $odkLinkService = app()->make(OdkLinkService::class);

        $result = $odkLinkService->createUser(User::where('email', 'test@example.com')->first(), 'password');

        dd($result);
    }
}
