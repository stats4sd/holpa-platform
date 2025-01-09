<div>
    <div>
        <div wire:click="$toggle('expanded')">
            <h5>{{ $language->language_label }}</h5>
        </div>

        <div class="transition ease-in-out delay-150 {{ $expanded ? 'visible' : 'hidden' }}">

        <p>
            (selected Locale goes here)
        </p>

        <p>
            (form for selecting a locale for this language goes here)
        </p>
        </div>
    </div>
</div>
