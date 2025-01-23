<?php

namespace App\Models\Xlsforms;

use App\Exports\XlsformExport\XlsformWorkbookExport;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Client\RequestException;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Stats4sd\FilamentOdkLink\Jobs\UpdateXlsformTitleInFile;
use Stats4sd\FilamentOdkLink\Services\OdkLinkService;

class Xlsform extends \Stats4sd\FilamentOdkLink\Models\OdkLink\Xlsform
{

    // overwrite to use the app model;
    public function xlsformTemplate(): BelongsTo
    {
        return $this->belongsTo(XlsformTemplate::class);
    }

    public function xlsformModules(): MorphMany
    {
        return $this->morphMany(XlsformModule::class, 'form');
    }

    public function syncWithTemplate(): void
    {

        // if the odk_project is not set, set it based on the given owner:
        $this->odk_project_id = $this->owner->odkProject->id;
        $this->has_latest_template = true;
        $this->saveQuietly();
    }

    /**
     * @throws RequestException
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function deployDraft(OdkLinkService $service, bool $withMedia = true): bool
    {

        // Generate the Xlsfile.
        $filePath = 'temp/' . $this->id . '/' . $this->title . '.xlsx';
        Excel::store(new XlsformWorkbookExport($this), $filePath, 'local');

        // Check for needed media files.
        //$this->requiredMedia();

        $this->addMediaFromDisk($filePath, disk: 'local')->toMediaCollection('xlsform_file');

        $this->syncWithTemplate();
        UpdateXlsformTitleInFile::dispatchSync($this);

        return parent::deployDraft($service);
    }

}
