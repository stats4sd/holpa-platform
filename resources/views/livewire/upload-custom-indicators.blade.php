<div>

   <div class="text-lg font-bold ">
      Upload custom indicators
   </div>
   
   @if(!$uploadedFile)
      <div class="px-8 py-4"> 
         <div class="w-4/6 mx-auto">
            <div class="p-6 bg-white rounded-lg">
               {{ $this->form }}
            </div>
         </div>
      </div>

      <div class="flex justify-center mt-6">
        <button wire:click="uploadFile" 
            class="buttona">
            Upload list
        </button>
      </div>

   @else
      <div class="pt-4">
         The custom indicators have been uploaded. See below for details. To edit the uploaded custom indicators,
         delete the file below and upload the updated file.
      </div>
      <div class="px-10 py-4"> 
         <div class="bg-white shadow rounded-lg">
            {{ $this->table }}
         </div>
      </div>
    @endif

</div>