<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        /**
         * Table to store all ODK raw submissions that get pulled from ODK Central
         */
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->string('odk_id')->unique();
            $table->foreignId('xlsform_version_id')->constrained('xlsform_versions');
            $table->timestamp('submitted_at');
            $table->string('submitted_by')->nullable();
            $table->longtext('content'); // This is explicitly not json so the ordering of variables is preserved (at the expense of not being able to query the content in SQL);
            $table->json('errors')->nullable();
            $table->boolean('processed')->default(0);

            // what data model entries were created when processing this submission? e.g., if the application has custom data maps that populate tables from processed submissions.
            $table->json('entries')->nullable();

            $table->boolean('draft_data')->default(0);
            $table->boolean('test_data')->default(0);



            // HOLPA CUSTOM
            $table->timestamp('survey_started_at')->nullable();
            $table->timestamp('survey_ended_at')->nullable();
            $table->float('survey_duration')->nullable()->comment('The time difference between survey start time and survey end time in minutes');


            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
