<div class="rounded shadow-lg p-6  actionblock">
    <!-- Heading -->
    <h3 class="">
        {{ $heading }}
    </h3>
    
    <div class="flex justify-between items-center mt-2">
        <!-- Description -->
        <p class="max-w-6xl">
            {!! $description !!}
        </p>
        
        <!-- Button (Right) -->
        <a href="{{ $url }}" 
           class="buttona">
            {{ $buttonLabel }}
        </a>
    </div>
</div>