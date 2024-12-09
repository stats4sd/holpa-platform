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

</div>