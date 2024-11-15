<x-filament-panels::page class="bg-white shadow-xl px-10 h-full">
    
    @livewire('page-header-with-instructions', [
        'instructions' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ac venenatis elit. Vivamus non urna ac turpis hendrerit tincidunt ut eget risus. Curabitur sagittis, ex a consectetur convallis, libero nisi efficitur sapien, non eleifend enim lectus vel leo. Morbi tincidunt libero ut nunc scelerisque, eget fringilla nulla volutpat. Aliquam feugiat massa sit amet arcu convallis, et iaculis ligula facilisis. Etiam accumsan magna et ipsum facilisis, at malesuada nulla ornare.',
        'videoUrl' => 'https://www.youtube.com/embed/VIDEO_ID'
    ])

    @livewire('rounded-section', [
        'heading' => 'View and manage survey',
        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ac venenatis elit. Vivamus non urna ac turpis hendrerit tincidunt ut eget risus.',
        'buttonLabel' => 'Update',
        'url' => 'url_to_be_added_here'
    ])

    @livewire('rounded-section', [
        'heading' => 'View and manage submissions',
        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ac venenatis elit. Vivamus non urna ac turpis hendrerit tincidunt ut eget risus.',
        'buttonLabel' => 'Update',
        'url' => 'url_to_be_added_here'
    ])

</x-filament-panels::page>