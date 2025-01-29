<?php

use App\Livewire\CoverPage;
use Illuminate\Support\Facades\Route;

// redirect user from root path to app panel login page
Route::get('/', CoverPage::class)->name('cover-page');

// when user logout from program admin panel, redirect user to app panel login pager
Route::get('/program/login', function () {
    return redirect('app');
})->name('filament.program.auth.login');

// when user logout from admin panel, redirect user to app panel login pager
Route::get('/admin/login', function () {
    return redirect('app');
})->name('filament.admin.auth.login');
