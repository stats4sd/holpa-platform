<div class="{{ $expanded ? 'border border-primary-500' : '' }}">
    <div class="py-4 px-8 border border-gray-200 flex justify-between items-center space-x-8 bg-gray-50">
        <div class="w-full flex justify-start">
            <h5 class="w-full md:w-1/2 lg:w-1/4">{{ $language->language_label }}</h5>
            <h5 class="w-full md:w-1/2 lg:w-1/4">{{ $selectedLocale->description ?? 'No translation selected' }}</h5>

        </div>
        <div class="self-end">
            <button class="buttona text-nowrap" wire:click="$toggle('expanded')">Select Translation</button>
        </div>
    </div>

    <div class="py-4 px-8 border border-gray-200 transition ease-in-out delay-150 {{ $expanded ? 'visible' : 'hidden' }}">
        {{ $this->table }}
    </div>
</div>
