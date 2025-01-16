<div class="space-y-8">
    <p>You can review the text for both ODK forms by downloading the files below.</p>

    @if($locale->is_default)
        <p>This translation is from the original ODK Forms, and cannot be directly edited. If you wish to create your own version of {{ $locale->language->name }}, you may add a new blank translation or duplicate this one and edit it.</p>
    @elseif($locale->createdBy !== \App\Services\HelperService::getSelectedTeam())
        <p>This translation was uploaded by another team as a contribution to HOLPA. You cannot edit this translation directly, but you may duplicate it and edit your version if you wish.</p>
    @else
        <p>To edit this translation, click edit below. To keep this version as-is and create a new version to edit, click duplicate.</p>
    @endif

    {{-- TODO: add 'download blank version'?? --}}



</div>
