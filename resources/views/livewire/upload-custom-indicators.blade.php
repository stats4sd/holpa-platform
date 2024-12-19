<div>

   <div class="text-lg font-bold ">
      Upload custom indicators
   </div>

   @if(!$uploadedFileHH || !$uploadedFileFW)
      <div class="px-12 py-4">
         <div class="w-4/6 mx-auto">
            <div class="p-6 bg-white rounded-lg">
               {{ $this->form }}
            </div>
         </div>
      </div>

      <div class="flex justify-center mt-6">
         <button wire:click="uploadFiles"
            class="buttona">
            Upload list
         </button>

         @if ($errors->has('missing_file'))
            <div class="text-green pl-4">
               {{ $errors->first('missing_file') }}
            </div>
         @endif
      </div>

   @endif

   @if($uploadedFileHH || $uploadedFileFW)
      <div class="pt-4">
         The custom indicators have been uploaded, see below for details.
      </div>
      <div class="px-10 py-4">
         <div class="bg-white uploadedindicatorstable rounded-lg">
            {{ $this->table }}
         </div>
      </div>
    @endif

</div>
