<?php

test('an xlsform template is correctly imported and updated', function () {

    $this->xlsformTemplate = \App\Models\XlsformTemplate::forceCreateQuietly([
        'title' => 'Test Template',
    ]);

    $import = new \App\Imports\XlsformTemplateWorksheetImport($this->xlsformTemplate);
    \Maatwebsite\Excel\Facades\Excel::import($import, 'tests/assets/odk-example-form-1.xlsx');

    // check that the survey rows were imported correctly
    $this->assertDatabaseCount('survey_rows', 3);
    $this->assertDatabaseCount('xlsform_template_languages', 2);
    $this->assertDatabaseCount('language_strings', 9);

    // ******** test that the xlsform template is updated correctly ******** //
    $import = new \App\Imports\XlsformTemplateWorksheetImport($this->xlsformTemplate);
    \Maatwebsite\Excel\Facades\Excel::import($import, 'tests/assets/odk-example-form-2.xlsx');

    // check that the survey rows were updated correctly
    $this->assertDatabaseCount('xlsform_template_languages', 2);
    $this->assertDatabaseCount('language_strings', 12);

    // check that the template languages are not marked as needing an update
    $this->assertDatabaseHas('xlsform_template_languages', [
        'xlsform_template_id' => $this->xlsformTemplate->id,
        'language_id' => \App\Models\Language::where('iso_alpha2', 'en')->first()->id,
        'needs_update' => 0,
    ]);

    $this->assertDatabaseHas('xlsform_template_languages', [
        'xlsform_template_id' => $this->xlsformTemplate->id,
        'language_id' => \App\Models\Language::where('iso_alpha2', 'fr')->first()->id,
        'needs_update' => 0,
    ]);
});

test('a language not in the xlsform template import is marked as needing an update', function () {


    $this->xlsformTemplate = \App\Models\XlsformTemplate::forceCreateQuietly([
        'title' => 'Test Template',
    ]);

    // add a new template language to the xlsform template
    $this->xlsformTemplate->xlsformTemplateLanguages()->create([
        'language_id' => \App\Models\Language::where('iso_alpha2', 'es')->first()->id,
    ]);

    // ******** test that survey rows and language strings are deleted correctly ******* //
    $import = new \App\Imports\XlsformTemplateWorksheetImport($this->xlsformTemplate);
    \Maatwebsite\Excel\Facades\Excel::import($import, 'tests/assets/odk-example-form-3.xlsx');

    $this->assertDatabaseCount('survey_rows', 3);
    $this->assertDatabaseCount('xlsform_template_languages', 3);
    $this->assertDatabaseCount('language_strings', 10);

    // ******** test that the xlsform template languages are marked as needing an update updated correctly ******** //
    // check that the template languages are not marked as needing an update
    $this->assertDatabaseHas('xlsform_template_languages', [
        'xlsform_template_id' => $this->xlsformTemplate->id,
        'language_id' => \App\Models\Language::where('iso_alpha2', 'en')->first()->id,
        'needs_update' => 0,
    ]);

    $this->assertDatabaseHas('xlsform_template_languages', [
        'xlsform_template_id' => $this->xlsformTemplate->id,
        'language_id' => \App\Models\Language::where('iso_alpha2', 'fr')->first()->id,
        'needs_update' => 0,
    ]);


    $this->assertDatabaseHas('xlsform_template_languages', [
        'xlsform_template_id' => $this->xlsformTemplate->id,
        'language_id' => \App\Models\Language::where('iso_alpha2', 'es')->first()->id,
        'needs_update' => 1,
    ]);


});
