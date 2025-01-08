<div class="mb-8">
    <div class="pb-4">
        <h3 class=" mb-4">Survey Languages</h3>
    </div>

    @foreach($languages as $language)
        <livewire:team-language-row :language="$language" :key="$language->id" :team="$team"/>
    @endforeach

</div>
