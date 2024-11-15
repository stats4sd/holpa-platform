<x-filament-panels::page class="bg-white shadow-xl px-10 h-full">
    
    @livewire('page-header-with-instructions', [
        'instructions' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ac venenatis elit. Vivamus non urna ac turpis hendrerit tincidunt ut eget risus. Curabitur sagittis, ex a consectetur convallis, libero nisi efficitur sapien, non eleifend enim lectus vel leo. Morbi tincidunt libero ut nunc scelerisque, eget fringilla nulla volutpat. Aliquam feugiat massa sit amet arcu convallis, et iaculis ligula facilisis. Etiam accumsan magna et ipsum facilisis, at malesuada nulla ornare.',
        'videoUrl' => 'https://www.youtube.com/embed/VIDEO_ID'
    ])

    @livewire('rounded-section', [
        'heading' => 'Download data',
        'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ac venenatis elit. Vivamus non urna ac turpis hendrerit tincidunt ut eget risus.',
        'buttonLabel' => 'Download CSV',
        'url' => 'url_to_be_added_here'
    ])

</x-filament-panels::page>