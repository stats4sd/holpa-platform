<x-filament-panels::page>

    <div class="text-lg">
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ac venenatis elit. Vivamus non urna ac turpis hendrerit tincidunt ut eget risus.
            Curabitur sagittis, ex a consectetur convallis, libero nisi efficitur sapien, non eleifend enim lectus vel leo.
        </p>
    </div>

    <div class="grid grid-cols-3 gap-6">
        @livewire('rounded-square', [
            'heading' => 'UPLOAD LOCAL INDICATORS',
            'description' => 'Upload the local indicators you identified in the LISP workshop.',
            'class' => 'flex-1'
        ])

        @livewire('rounded-square', [
            'heading' => 'MATCH WITH EXISTING GLOBAL INDICATORS',
            'description' => 'Browse the list of indicators already available in the HOLPA global survey, and match them to your identified local indicators.',
            'class' => 'flex-1'
        ])

        @livewire('rounded-square', [
            'heading' => 'ADD CUSTOM INDICATORS',
            'description' => 'If your local indicators do not already exist in the global survey, you can add them as custom indicators.',
            'class' => 'flex-1'
        ])
    </div>

</x-filament-panels::page>
