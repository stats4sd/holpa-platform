<div>

    <div class="text-lg font-bold text-green pb-8">
        ADD CUSTOM INDICATORS
    </div>
        
    <div class="text-black pb-8">
        Below are the remaining indicators that have not been mapped to an existing indicator in the survey. For each of the below
        indicators, confirm if you would like to collect data for these indicators <b>in addition to the global required indicators and
        selected global optional indicators,</b> then download the template.
    </div>

    <div class="bg-blue rounded-full p-6 flex flex-col gap-4">
        <div class="flex items-start gap-4">
            <div class="text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-16 w-16">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
            </div>
            <div class="flex-1 text-gray-800">
                Please make sure that the below indicators are not covered by any existing indicators in the global survey before proceeding. 
                You can review the 'Match with existing global indicators' page if you are not sure.
            </div>
        </div>
        <div class="text-right pr-4">
            <a href="url_to_be_added_here" 
                class="bg-green text-white px-4 py-2 rounded-full hover-effect">
                Review global indicators
            </a>
        </div>
    </div>

    <div class="text-lg font-bold text-green py-8">
        Confirm custom indicators
    </div>

   {{ $this->table }}

   <div class="flex justify-center gap-4 py-8">
        <button wire:click="resetIndicators" 
                class="bg-green text-white py-2 px-6 rounded-lg hover-effect">
            Reset
        </button>
        <button wire:click="downloadTemplate"
                class="bg-green text-white py-2 px-6 rounded-lg hover-effect">
            Download template
        </button>
    </div>

    <livewire:upload-custom-indicators />

</div>