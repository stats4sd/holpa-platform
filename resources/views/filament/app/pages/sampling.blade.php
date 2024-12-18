<?php
use App\Filament\App\Clusters\LocationLevels\Resources\FarmResource;

$farmUrl = url(FarmResource::getUrl());

?>

<x-filament-panels::page class="bg-white shadow-xl px-10 h-full">

    <livewire:page-header-with-instructions
        instructions='Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut ac venenatis elit. Vivamus non urna ac turpis hendrerit tincidunt ut eget risus. Curabitur sagittis, ex a consectetur convallis, libero nisi efficitur sapien, non eleifend enim lectus vel leo. Morbi tincidunt libero ut nunc scelerisque, eget fringilla nulla volutpat. Aliquam feugiat massa sit amet arcu convallis, et iaculis ligula facilisis. Etiam accumsan magna et ipsum facilisis, at malesuada nulla ornare.'
        videoUrl='https://www.youtube.com/embed/VIDEO_ID'
    />

    <livewire:rounded-section
        heading='Manage Hierarchy'
        description='Manage the location levels (or other strata) in your sampling frame.'
        buttonLabel='Update'
        url='location-levels'
    />

    <livewire:rounded-section
        heading='List of farms'
        description='Add or import details of the farms you will visit to give the questionnaire.'
        buttonLabel='Update'
        :url='$farmUrl'
    />

</x-filament-panels::page>
