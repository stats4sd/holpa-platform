<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Actions\ExportDataAction;

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

// add a route for data export
Route::get('export', [ExportDataAction::class, 'export']);
