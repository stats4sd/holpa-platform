<div>
    <div class="text-lg font-bold text-green pb-8">
        UPLOAD LOCAL INDICATORS
    </div>

    @if(!$uploadedFile)
        <div class="text-black pb-6">
            Use the box below to upload the completed indicator template containing the local indicators identified
            during the LISP workshop.
            <br>
            If needed, you can download the template here.
        </div>

        <div class="px-8 py-4"> 
            <div class="max-w-lg mx-auto">
                <div class="p-6 bg-white shadow rounded-lg">
                    {{ $this->form }}
                </div>

                <div class="flex justify-center mt-6">
                    <button wire:click="uploadFile" 
                            class="bg-green text-white py-2 px-6 rounded-lg hover-effect">
                        Upload list
                    </button>
                </div>
            </div>
        </div>
    @else
    <div class="px-10 py-4"> 
            <div class="bg-white shadow rounded-lg">
                {{ $this->table }}
            </div>
        </div>
    @endif
</div>