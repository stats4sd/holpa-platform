<?php

use Illuminate\Support\Facades\Route;

use Stats4sd\FilamentTeamManagement\Filament\App\Pages\Register;
use Stats4sd\FilamentTeamManagement\Filament\App\Pages\Roleregister;
use Stats4sd\FilamentTeamManagement\Filament\App\Pages\Programregister;

// user registration form for team-invites
Route::get('register', Register::class)
    ->name('filament.app.register')
    ->middleware('signed');

// user registration form for role-invites
Route::get('roleregister', Roleregister::class)
    ->name('filament.app.roleregister')
    ->middleware('signed');

// user registration form for program-invites
Route::get('programregister', Programregister::class)
    ->name('filament.app.programregister')
    ->middleware('signed');

// redirect user from root path to app panel login page
Route::get('/', function () {
    return redirect('app');
});

// when user logout from program admin panel, redirect user to app panel login pager
Route::get('/program/login', function () {
    return redirect('app');
})->name('filament.program.auth.login');

// when user logout from admin panel, redirect user to app panel login pager
Route::get('/admin/login', function () {
    return redirect('app');
})->name('filament.admin.auth.login');
