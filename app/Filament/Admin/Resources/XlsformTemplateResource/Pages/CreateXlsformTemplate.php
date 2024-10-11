<?php

namespace App\Filament\Admin\Resources\XlsformTemplateResource\Pages;

use Filament\Forms\Get;
use App\Models\XlsformTemplate;
use Filament\Forms\Components\Wizard\Step;
use App\Filament\Admin\Resources\XlsformTemplateResource;
use Stats4sd\FilamentOdkLink\Filament\Resources\XlsformTemplateResource\Pages\CreateXlsformTemplate as OdkLinkCreateXlsformTemplate;

class CreateXlsformTemplate extends OdkLinkCreateXlsformTemplate
{
    protected static string $resource = XlsformTemplateResource::class;

    public function getSteps(): array
    {
        return [
            Step::make('1. Xlsform')
                ->description('Upload your XLSForm file and give it a title')
                ->schema(
                    XlsformTemplateResource::getCreateFields(),
                )
                ->afterValidation(function (Get $get) {

                    $xlsformTemplate = XlsformTemplate::create([
                        'title' => $get('title'),
                    ]);

                    $files = $get('xlsfile');

                    $xlsformTemplate->addMedia(collect($files)->first())->toMediaCollection('xlsform_file');

                    // this was being triggered on afterCreate. Call it here instead/as well.
                    $xlsformTemplate = $this->processRecord($xlsformTemplate);

                    if (!$xlsformTemplate) {
                        return redirect($this->getResource()::getUrl('create') . '?step=1-xlsform&title=' . urlencode($get('title')));
                    }

                    return redirect($this->getResource()::getUrl('edit', ['record' => $xlsformTemplate]));

                }),

            Step::make('2. Add Media Files')
                ->description('Add any static media required by the form')
                ->schema([]),
            Step::make('3. Link Required Datasets')
                ->description('Add / link external datasets for lookup tables')
                ->schema([]),
            Step::make('4. Review Xlsform Structure')
                ->description('How should the collected data be handled?')
                ->schema([]),
        ];
    }
}
