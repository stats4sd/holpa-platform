# HOLPA Platform

## Setup

- clone this repo
- inside the cloned repo folder, run:
  - `composer install`
  - `npm install`
- create your local .env file:
  - `cp .env.example .env`
  - update the DB_* variables, the APP_URL variables. 
  - make sure the ODK_* variables are set so the system can connect to an ODK Central server.

## Development

There are multiple sets of database seeders available for testing different parts of the app:

- `php artisan db:seed` will run the default seeders and populate the required lookup tables. If you are on APP_ENV=local, it will also seed the database with test users and teams.
- `php artisan db:seed TestTemplatesSeeder` will add test HOLPA ODK form templates, and populate all the xlsform-related tables: 
  - ChoiceListEntry
  - ChoiceList
  - LanguageString
  - RequiredMedia
  - SurveyRow
  - XlsformModule
  - XlsformModuleVersionLocal
  - XlsformModuleVersion
  - Xlsform
  - XlsformTemplateSection
  - XlsformTemplate

- `php artisan db:seed TestOdkStuffSeeder` will add entries assuming you are connecting to the Stats4SD test ODK Central server. It will link your local teams to specific ODK projects, and assumes you have the env variable: ODK_PLATFORM_PROJECT_ID=1390.
