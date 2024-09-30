<?php

use Illuminate\Support\Facades\Route;
use App\Filament\App\Pages\Register;

Route::get('register', Register::class)
    ->name('filament.app.register')
    ->middleware('signed');
