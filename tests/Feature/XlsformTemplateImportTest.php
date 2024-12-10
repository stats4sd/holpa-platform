<?php

use App\Listeners\HandleXlsformTemplateAdded;
use App\Models\XlsformTemplates\LanguageString;

test('an xlsform template is correctly imported and updated', function () {

    $this->xlsformTemplate = \App\Models\XlsformTemplates\XlsformTemplate::forceCreateQuietly([
        'title' => 'Test Template',
    ]);

    (new HandleXlsformTemplateAdded())->processXlsformTemplate('tests/assets/odk-example-form-1.xlsx', $this->xlsformTemplate);

    ray(LanguageString::all()
        ->map(fn($languageString) => [
            'text' => $languageString->text,
            'language' => $languageString->xlsformTemplateLanguage->language->iso_alpha2,
            'type' => $languageString->languageStringType->name,
        ])
        ->groupBy('language'));

    // check that the survey rows were imported correctly
    $this->assertDatabaseCount('survey_rows', 3);
    $this->assertDatabaseCount('choice_lists', 3);
    $this->assertDatabaseCount('choice_list_entries', 12);
    $this->assertDatabaseCount('xlsform_template_languages', 2);
    $this->assertDatabaseCount('language_strings', 21);


    // check for a specific entry
    $choiceEntry = \App\Models\XlsformTemplates\ChoiceListEntry::firstWhere('name', '=', 'juice');

    $this->assertEquals('5', $choiceEntry->properties['filter2']);

    // ******** test that the xlsform template is updated correctly ******** //
    (new HandleXlsformTemplateAdded())->processXlsformTemplate('tests/assets/odk-example-form-2.xlsx', $this->xlsformTemplate);

    // check that the survey rows were updated correctly
    $this->assertDatabaseCount('xlsform_template_languages', 2);
    $this->assertDatabaseCount('language_strings', 24);

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


    $this->xlsformTemplate = \App\Models\XlsformTemplates\XlsformTemplate::forceCreateQuietly([
        'title' => 'Test Template',
    ]);

    // add a new template language to the xlsform template
    $this->xlsformTemplate->xlsformTemplateLanguages()->create([
        'language_id' => \App\Models\Language::where('iso_alpha2', 'es')->first()->id,
    ]);

    // ******** test that survey rows and language strings are deleted correctly ******* //
    (new HandleXlsformTemplateAdded())->processXlsformTemplate('tests/assets/odk-example-form-3.xlsx', $this->xlsformTemplate);


    $this->assertDatabaseCount('survey_rows', 3);
    $this->assertDatabaseCount('xlsform_template_languages', 3);
    $this->assertDatabaseCount('language_strings', 22);

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
