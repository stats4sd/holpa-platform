<div>

   <div class="text-lg font-bold text-green">
      Upload custom indicators
   </div>
   
   @if(!$uploadedFileHH && !$uploadedFileFW)
      <div class="px-8 py-4"> 
         <div class="max-w-3xl mx-auto">
            <div class="p-6 bg-white shadow rounded-lg">
               {{ $this->form }}
            </div>
         </div>
      </div>

      <div class="flex justify-center mt-6">
        <button wire:click="uploadFiles" 
            class="bg-green text-white py-2 px-6 rounded-lg hover-effect">
            Upload
        </button>
      </div>

   @else
      <div class="pt-4">
         The custom indicators have been uploaded. See below for details. To edit the uploaded custom indicators,
         delete the file(s) below and upload the updated file(s).
      </div>
      <div class="px-10 py-4"> 
         <div class="bg-white shadow rounded-lg">
            {{ $this->table }}
         </div>
      </div>
    @endif

</div>