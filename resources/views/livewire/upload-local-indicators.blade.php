<div>
    <div class="text-lg font-bold text-green pb-4">
        UPLOAD LOCAL INDICATORS
    </div>

    @if(!$uploadedFile)
        <div class="text-black pb-6">
            Use the box below to upload the completed indicator template containing the local indicators identified
            during the LISP workshop.
            <br>
            If needed, you can <a href="/files/HOLPA_indicator_template.xlsx" class="text-green">download the template here</a>.
        </div>

        <div class="px-8 py-4"> 
            <div class="w-4/6 mx-auto  ">
                <div class="p-6 bg-white  ">
                    {{ $this->form }}
                </div>

                <div class="flex justify-center mt-6">
                    <button wire:click="uploadFile" 
                            class="buttona">
                        Upload list
                    </button>
                </div>
            </div>
        </div>
    @else
        <div>
            An indicator list has been uploaded. See below for details. To edit the uploaded indicators,
            delete the file below and upload the updated file.
        </div>
        <div class="px-10 py-4"> 
            <div class="bg-white  rounded-lg">
                {{ $this->table }}
            </div>
        </div>
    @endif
</div>