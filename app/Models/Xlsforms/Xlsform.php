<?php

namespace App\Models\Xlsforms;

use App\Models\XlsformModule;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Client\RequestException;
use App\Models\XlsformTemplates\ChoiceListEntry;
use App\Models\XlsformTemplates\XlsformTemplate;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Exports\XlsformExport\XlsformWorkbookExport;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Stats4sd\FilamentOdkLink\Services\OdkLinkService;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Stats4sd\FilamentOdkLink\Jobs\UpdateXlsformTitleInFile;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;

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

        ray('generating custom xlsform file...');

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
