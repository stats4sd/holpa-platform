<?php

use App\Models\Team;
use App\Models\User;

return [

    'models' => [

        /**
         * Tells the system which Team model in use.
         * By default, it is "\Stats4sd\FilamentOdkLink\Models\TeamManagement\Team"
         * User can define custom Team model in .env file config item "ODK_TEAM_MODEL"
         */
        'team_model' => env('ODK_TEAM_MODEL', Team::class),
        'user_model' => env('ODK_USER_MODEL', User::class),

    ],

    'odk' => [

        /**
         * Tells the system which Aggregation system is in use. Possible values are:
         * - odk-central
         */
        'aggregator' => env('ODK_SERVICE', 'odk-central'),

        /**
         * The base url for the service (without the trailing '/').
         * If you use the public Kobotoolbox, this will be
         *  - 'https://kf.kobotoolbox.org' or
         *  - 'https://kobo.humanitarianresponse.info'
         *
         * If you use a custom installation of ODK Central or Kobotoolbox, it will be the base url to your service.
         */
        'url' => env('ODK_URL', null),
        'base_endpoint' => env('ODK_URL', '').'/v1',

        'platform_project_id' => env('ODK_PLATFORM_PROJECT_ID', null),

        /**
         * Username and password for the main platform account
         * The platform requires a 'primary' user account on the ODK Central / KoboToolbox server to manage deployments of ODK forms.
         * This account will *own* every form published by the platform.
         *
         * We recommend not using an account that individuals typically use or have access to, to avoid mismatch between forms deployed and forms in the Laravel database.
         */
        'username' => env('ODK_USERNAME', ''),
        'password' => env('ODK_PASSWORD', ''),

        // the password to be used for individual project accounts
        // TODO: consider options for allowing users to set their own passwords (which we cannot keep in plain text, so we must ask the user for it every time).
        // TODO: consider how to hash this - maybe each project has a unique seed that combines with the main ODK_PASSWORD to generate this.
        'project-password' => env('ODK_PROJECT_PASSWORD', env('ODK_PASSWORD')),
    ],

    'storage' => [
        'xlsforms' => config('filesystem.default', 'local'),
        'media' => config('filesystem.default', 'local'),
    ],

    'roles' => [
        // the role that a user must have in order to see *all* forms, and not just the ones owned by an entity linked to the user.
        'xlsform-admin' => env('XLSFORM_ADMIN_ROLE', 'admin'),
    ],

    'owners' => [
        'main_type' => env('MAIN_OWNER_TYPE', 'team'),
    ],

    'submission' => [

        // The class and method used to process the submissions.
        // The method should be:
        // - a public static function;
        // - accept a OdkLink\Models\Submission object as the only required variable.;
        'process_method' => [
            'class' => env('SUBMISSION_PROCESS_CLASS', null),
            'method' => env('SUBMISSION_PROCESS_METHOD', null),
        ],

        // The class and method used to process foreign key records in the submissions.
        'foreign_key_process_method' => [
            'class' => env('SUBMISSION_FOREIGN_KEY_PROCESS_CLASS', null),
            'method' => env('SUBMISSION_FOREIGN_KEY_PROCESS_METHOD', null),
        ],

    ],
];
