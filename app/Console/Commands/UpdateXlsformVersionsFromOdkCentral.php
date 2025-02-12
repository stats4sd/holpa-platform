<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Http;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Xlsform;
use Illuminate\Console\Command;
use Stats4sd\FilamentOdkLink\Services\OdkLinkService;

class UpdateXlsformVersionsFromOdkCentral extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-xlsform-versions-from-odk-central';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A command to use when testing ODK Central integration. In cases where the version of the xlsform is updated on ODK Central without being updated locally, this command will update the xlsform_versions table locally with data from ODK Central';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // for each xlsform, get all the versions from ODK Central
        Xlsform::all()->each(function (Xlsform $xlsform) {

            $odkLinkService = app()->make(OdkLinkService::class);

            $baseUrl = config('filament-odk-link.odk.base_endpoint');
            $projectId = $xlsform->odk_project_id;
            $formId = $xlsform->odk_id;

                $versions = Http::withToken($odkLinkService->authenticate())
                    ->get($baseUrl . '/projects/' . $projectId . '/forms/' . $formId . '/versions')
                    ->throw()
                    ->json();

                collect($versions)->each(function ($version) use ($xlsform) {
                   $versionName = $version['version'];

                   if($xlsform->xlsformVersions()->where('odk_version', $versionName)->count() === 0) {
                       $xlsform->xlsformVersions()->create([
                           'version' => $versionName,
                           'odk_version' => $versionName,
                           // schema will be null, but I don't think we use the xlsform version schema at the moment.
                       ]);
                   }
                });

        });
    }
}
