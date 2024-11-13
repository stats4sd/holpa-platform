<?php

test('an xlsform template is correctly imported and updated', function() {

    $this->xlsformTemplate = \App\Models\XlsformTemplate::forceCreateQuietly([
        'title' => 'Test Template',
    ]);

    $import = new \App\Imports\XlsformTemplateImport($this->xlsformTemplate);

    \Maatwebsite\Excel\Facades\Excel::import($import, 'tests/assets/odk-example-form-1.xlsx');

    // check that the survey rows were imported correctly
    $this->assertDatabaseCount('survey_rows', 3);
    $this->assertDatabaseCount('xlsform_template_languages', 2);
    $this->assertDatabaseCount('language_strings', 9);

    // ******** test that the xlsform template is updated correctly ******** //
    \Maatwebsite\Excel\Facades\Excel::import($import, 'tests/assets/odk-example-form-2.xlsx');

    // check that the survey rows were updated correctly
    $this->assertDatabaseCount('survey_rows', 4);
    $this->assertDatabaseCount('xlsform_template_languages', 2);
    $this->assertDatabaseCount('language_strings', 12);


    // ******** test that survey rows and language strings are deleted correctly ******* //
    \Maatwebsite\Excel\Facades\Excel::import($import, 'tests/assets/odk-example-form-3.xlsx');

    $this->assertDatabaseCount('survey_rows', 3);
    $this->assertDatabaseCount('xlsform_template_languages', 2);
    $this->assertDatabaseCount('language_strings', 10);


});
