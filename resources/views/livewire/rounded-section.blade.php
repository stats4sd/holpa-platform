<div class="rounded-xl shadow-xl p-6 bg-grey">
    <!-- Heading -->
    <h2 class="text-lg font-bold text-green">
        {{ $heading }}
    </h2>
    
    <div class="flex justify-between items-center mt-2">
        <!-- Description -->
        <p class="max-w-6xl">
            {!! $description !!}
        </p>
        
        <!-- Button (Right) -->
        <a href="{{ $url }}" 
           class="px-4 py-2 text-white bg-green font-semibold rounded-lg text-center">
            {{ $buttonLabel }}
        </a>
    </div>
</div>