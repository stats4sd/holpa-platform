@php

$scope = $scopes[0];

@dump($scope);

// get the snake-case name of the class
$blade = \Illuminate\Support\Str::afterLast('\\', $scope);
$blade = \Illuminate\Support\Str::snake($blade, '-');

@endphp

@include('filament.app.pages.info-panels.' . $blade)
