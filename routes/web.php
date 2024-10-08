<?php

use Illuminate\Support\Facades\Route;
use App\Filament\App\Pages\Register;
use App\Filament\App\Pages\Roleregister;

// user registration form for team-invites
Route::get('register', Register::class)
    ->name('filament.app.register')
    ->middleware('signed');

// user registration form for role-invites
Route::get('roleregister', Roleregister::class)
    ->name('filament.app.roleregister')
    ->middleware('signed');
