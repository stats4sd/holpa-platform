<?php

namespace Database\Seeders\TestTemplates;

use Illuminate\Database\Seeder;

class XlsformsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('xlsforms')->delete();

        \DB::table('xlsforms')->insert(array (
            0 =>
            array (
                'id' => 8,
                'xlsform_template_id' => 1,
                'owner_id' => 1,
                'owner_type' => 'App\\Models\\Team',
                'odk_project_id' => 1434,
                'title' => 'Test Mini HOLPA Household Survey',
                'odk_id' => NULL,
                'odk_draft_token' => NULL,
                'odk_version_id' => NULL,
                'has_draft' => NULL,
                'is_active' => NULL,
                'enketo_draft_id' => NULL,
                'enketo_id' => NULL,
                'processing' => 0,
                'odk_error' => NULL,
                'schema' => NULL,
                'has_latest_template' => 1,
                'has_latest_media' => 1,
                'created_at' => '2025-01-07 17:15:45',
                'updated_at' => '2025-01-07 17:15:45',
            ),
            1 =>
            array (
                'id' => 9,
                'xlsform_template_id' => 1,
                'owner_id' => 2,
                'owner_type' => 'App\\Models\\Team',
                'odk_project_id' => 1435,
                'title' => 'Test Mini HOLPA Household Survey',
                'odk_id' => NULL,
                'odk_draft_token' => NULL,
                'odk_version_id' => NULL,
                'has_draft' => NULL,
                'is_active' => NULL,
                'enketo_draft_id' => NULL,
                'enketo_id' => NULL,
                'processing' => 0,
                'odk_error' => NULL,
                'schema' => NULL,
                'has_latest_template' => 1,
                'has_latest_media' => 1,
                'created_at' => '2025-01-07 17:15:45',
                'updated_at' => '2025-01-07 17:15:45',
            ),
            2 =>
            array (
                'id' => 10,
                'xlsform_template_id' => 1,
                'owner_id' => 3,
                'owner_type' => 'App\\Models\\Team',
                'odk_project_id' => 1436,
                'title' => 'Test Mini HOLPA Household Survey',
                'odk_id' => NULL,
                'odk_draft_token' => NULL,
                'odk_version_id' => NULL,
                'has_draft' => NULL,
                'is_active' => NULL,
                'enketo_draft_id' => NULL,
                'enketo_id' => NULL,
                'processing' => 0,
                'odk_error' => NULL,
                'schema' => NULL,
                'has_latest_template' => 1,
                'has_latest_media' => 1,
                'created_at' => '2025-01-07 17:15:45',
                'updated_at' => '2025-01-07 17:15:45',
            ),
            3 =>
            array (
                'id' => 11,
                'xlsform_template_id' => 2,
                'owner_id' => 1,
                'owner_type' => 'App\\Models\\Team',
                'odk_project_id' => 1434,
                'title' => 'Test Mini HOLPA Fieldwork Survey',
                'odk_id' => NULL,
                'odk_draft_token' => NULL,
                'odk_version_id' => NULL,
                'has_draft' => NULL,
                'is_active' => NULL,
                'enketo_draft_id' => NULL,
                'enketo_id' => NULL,
                'processing' => 0,
                'odk_error' => NULL,
                'schema' => NULL,
                'has_latest_template' => 1,
                'has_latest_media' => 1,
                'created_at' => '2025-01-07 17:15:46',
                'updated_at' => '2025-01-07 17:15:46',
            ),
            4 =>
            array (
                'id' => 12,
                'xlsform_template_id' => 2,
                'owner_id' => 2,
                'owner_type' => 'App\\Models\\Team',
                'odk_project_id' => 1435,
                'title' => 'Test Mini HOLPA Fieldwork Survey',
                'odk_id' => NULL,
                'odk_draft_token' => NULL,
                'odk_version_id' => NULL,
                'has_draft' => NULL,
                'is_active' => NULL,
                'enketo_draft_id' => NULL,
                'enketo_id' => NULL,
                'processing' => 0,
                'odk_error' => NULL,
                'schema' => NULL,
                'has_latest_template' => 1,
                'has_latest_media' => 1,
                'created_at' => '2025-01-07 17:15:46',
                'updated_at' => '2025-01-07 17:15:46',
            ),
            5 =>
            array (
                'id' => 13,
                'xlsform_template_id' => 2,
                'owner_id' => 3,
                'owner_type' => 'App\\Models\\Team',
                'odk_project_id' => 1436,
                'title' => 'Test Mini HOLPA Fieldwork Survey',
                'odk_id' => NULL,
                'odk_draft_token' => NULL,
                'odk_version_id' => NULL,
                'has_draft' => NULL,
                'is_active' => NULL,
                'enketo_draft_id' => NULL,
                'enketo_id' => NULL,
                'processing' => 0,
                'odk_error' => NULL,
                'schema' => NULL,
                'has_latest_template' => 1,
                'has_latest_media' => 1,
                'created_at' => '2025-01-07 17:15:46',
                'updated_at' => '2025-01-07 17:15:46',
            ),
        ));


    }
}
