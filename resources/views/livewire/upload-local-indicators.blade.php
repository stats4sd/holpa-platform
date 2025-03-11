<div>
    <div class="text-lg font-bold text-green pb-4">
        UPLOAD LOCAL INDICATORS
    </div>


    <div class="text-black pb-6">
        On this page, please add the locally relevant indicators that you identified during the LISP workshop. You may either upload the indicators as an Excel file using the provided
        <a href="{{ url('files/HOLPA_indicator_template.xlsx') }}">template</a>, or enter each indicator manually into the table below.
    </div>

    <div class="px-8 py-4">
        <div class="mx-auto  ">
            <div class="p-6 bg-white  ">
                {{ $this->form }}
            </div>

            <div class="flex justify-center mt-6">
                <button wire:click="saveIndicators"
                        class="buttona">
                    Save
                </button>
            </div>
        </div>
    </div>
</div>
