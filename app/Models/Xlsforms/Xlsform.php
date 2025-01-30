<?php

namespace App\Models\Xlsforms;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Stats4sd\FilamentOdkLink\Exports\XlsformExport\XlsformWorkbookExport;
use Stats4sd\FilamentOdkLink\Jobs\UpdateXlsformTitleInFile;
use Stats4sd\FilamentOdkLink\Services\OdkLinkService;

class Xlsform extends \Stats4sd\FilamentOdkLink\Models\OdkLink\Xlsform
{

    // overwrite to use the app model;
    public function xlsformTemplate(): BelongsTo
    {
        return $this->belongsTo(XlsformTemplate::class);
    }


    /**
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
