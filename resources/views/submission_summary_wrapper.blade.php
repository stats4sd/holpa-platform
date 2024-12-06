@php

$locations = \App\Models\SampleFrame\Location::where('owner_id', \App\Services\HelperService::getSelectedTeam()->id)->get();

$submissions = $getRecord()->submissions;


// comment below code and hardcode temporary for testing
// TODO: revise to corresponding ODK variable in household form and fieldwork form
/*

$submissionsByLocations = $submissions->map(function(\Stats4sd\FilamentOdkLink\Models\OdkLink\Submission $submission) {

$submission->location_id = $submission->content['reg']['final_location_id'];

return $submission;
})
->groupBy('location_id');

$submissionsByEnumerators = $submissions->map(function(\Stats4sd\FilamentOdkLink\Models\OdkLink\Submission $submission) {

$submission->enumerator_id = $submission->content['survey_start']['inquirer'];

return $submission;
})->groupBy('enumerator_id');

*/

$submissionsByLocations = $submissions;
$submissionsByEnumerators = $submissions;

$submissionDurationLevel1 = $submissions->whereBetween('survey_duration', [0, 4.999999])->count();
$submissionDurationLevel2 = $submissions->whereBetween('survey_duration', [5, 29.999999])->count();
$submissionDurationLevel3 = $submissions->whereBetween('survey_duration', [30, 59.999999])->count();
$submissionDurationLevel4 = $submissions->whereBetween('survey_duration', [60, 120])->count();
$submissionDurationLevel5 = $submissions->where('survey_duration', '>', 120)->count();

@endphp

<div class="grid grid-cols-2 gap-8">

    <x-filament::section>
        <x-slot name="heading">
            <b>Submissions By Location (TODO)</b>
        </x-slot>

        @foreach($submissionsByLocations as $key => $locationFromSubmission)
        <div class="grid grid-cols-3 gap-3">
            <b class="text-right">{{ $locations->firstWhere('id', $key)?->name ?? $key }}</b>
            <span class="col-span-2">{{ $locationFromSubmission->count() }}</span>
        </div>
        @endforeach

    </x-filament::section>

    <x-filament::section>
        <x-slot name="heading">
            <b>Submissions By Enumerator (TODO)</b>
        </x-slot>

        @foreach($submissionsByEnumerators as $key => $enumeratorSubmissions)
        <div class="grid grid-cols-3 gap-3">
            <b class="text-right col-span-2">{{ \Illuminate\Support\Str::replace("_", " ", $key) }}</b>
            <span>{{ $enumeratorSubmissions->count() }}</span>
        </div>
        @endforeach

    </x-filament::section>

    <x-filament::section>
        <x-slot name="heading">
            <b>Submissions By Survey Duration</b>
        </x-slot>

        <div class="grid grid-cols-3 gap-3">
            <b class="text-right">0 - 5 Minutes</b>
            <span class="col-span-2">{{ $submissionDurationLevel1 }}</span>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <b class="text-right">5 - 30 Minutes</b>
            <span class="col-span-2">{{ $submissionDurationLevel2 }}</span>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <b class="text-right">30 - 60 Minutes</b>
            <span class="col-span-2">{{ $submissionDurationLevel3 }}</span>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <b class="text-right">60 - 120 Minutes</b>
            <span class="col-span-2">{{ $submissionDurationLevel4 }}</span>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <b class="text-right">&gt; 120 Minutes</b>
            <span class="col-span-2">{{ $submissionDurationLevel5 }}</span>
        </div>

    </x-filament::section>

</div>