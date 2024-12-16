<x-filament-panels::page >

<livewire:page-header-with-instructions
        instructions='Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ac venenatis elit. Vivamus non urna ac turpis hendrerit tincidunt ut eget risus. Curabitur sagittis, ex a consectetur convallis, libero nisi efficitur sapien, non eleifend enim lectus vel leo. Morbi tincidunt libero ut nunc scelerisque, eget fringilla nulla volutpat. Aliquam feugiat massa sit amet arcu convallis, et iaculis ligula facilisis. Etiam accumsan magna et ipsum facilisis, at malesuada nulla ornare.'
        videoUrl='https://www.youtube.com/embed/VIDEO_ID'
         /> 

<div id="languages">
<!-- Main Section -->
<div class="container mx-auto xl:px-12 ">
    <div class="surveyblocks px-10 h-full py-12">

    @livewire('team-locales-table')

    @livewire('locales-table')
    </div>

</x-filament-panels::page>
