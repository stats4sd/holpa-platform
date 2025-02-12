<?php

namespace Database\Seeders\TestOdkStuff;

use DB;
use Illuminate\Database\Seeder;

class MediaTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {


        DB::table('media')->delete();

        DB::table('media')->insert(array (
            0 =>
            array (
                'id' => 1,
                'model_type' => 'Stats4sd\\FilamentOdkLink\\Models\\OdkLink\\XlsformTemplate',
                'model_id' => 1,
                'uuid' => '62789a31-562d-4873-9b2e-5aa7a8c0a436',
                'collection_name' => 'xlsform_file',
                'name' => 'ODK Example 2025 - with nested repeat and modules',
                'file_name' => 'ODK-Example-2025---with-nested-repeat-and-modules.xlsx',
                'mime_type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'disk' => 'public',
                'conversions_disk' => 'public',
                'size' => 19107,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'generated_conversions' => '[]',
                'responsive_images' => '[]',
                'order_column' => 1,
                'created_at' => '2025-01-16 14:40:35',
                'updated_at' => '2025-01-16 14:40:35',
            ),
            1 =>
            array (
                'id' => 2,
                'model_type' => 'Stats4sd\\FilamentOdkLink\\Models\\OdkLink\\XlsformTemplate',
                'model_id' => 2,
                'uuid' => '02183c0b-6f17-4357-8e5a-64d1ecd5ab61',
                'collection_name' => 'xlsform_file',
                'name' => 'ODK Example 2025 - Version 2',
                'file_name' => 'ODK-Example-2025---Version-2.xlsx',
                'mime_type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'disk' => 'public',
                'conversions_disk' => 'public',
                'size' => 19962,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'generated_conversions' => '[]',
                'responsive_images' => '[]',
                'order_column' => 1,
                'created_at' => '2025-01-16 14:41:02',
                'updated_at' => '2025-01-16 14:41:02',
            ),
            2 =>
            array (
                'id' => 3,
                'model_type' => 'Stats4sd\\FilamentOdkLink\\Models\\OdkLink\\RequiredMedia',
                'model_id' => 1,
                'uuid' => '7375292f-748e-4547-80dc-0216a1b1cf4b',
                'collection_name' => 'default',
                'name' => 'coffee',
                'file_name' => 'coffee.png',
                'mime_type' => 'image/png',
                'disk' => 'local',
                'conversions_disk' => 'local',
                'size' => 3205,
                'manipulations' => '[]',
                'custom_properties' => '[]',
                'generated_conversions' => '[]',
                'responsive_images' => '[]',
                'order_column' => 1,
                'created_at' => '2025-01-16 14:41:36',
                'updated_at' => '2025-01-16 14:41:36',
            ),
        ));


    }
}
