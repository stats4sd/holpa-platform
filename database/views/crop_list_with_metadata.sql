SELECT
    crop_list_entries.id as id,
    if(choice_list_entries.owner_id is null, 1, 0) as `is_global_entry`,
    choice_list_entries.owner_id as team_id,
    choice_list_entries.name as name,
    language_strings.text as label_en,
    crop_list_entries.expected_yield as expected_yield,
    crop_list_entries.recommended_fert_use as recommended_fert_use

from crop_list_entries
left join choice_list_entries on crop_list_entries.choice_list_entry_id = choice_list_entries.id
left join language_strings on
    linked_entry_id = choice_list_entries.id
    # only label strings
    and language_strings.language_string_type_id = (SELECT id from language_string_types where name = 'label')
    # only english strings
    and xlsform_template_language_id = (SELECT id from xlsform_template_languages where xlsform_template_languages.language_id = (SELECT id from languages where iso_alpha2 = "en" limit 1) limit 1);
