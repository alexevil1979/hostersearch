<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Affiliate / Visit Site redirect stub (implemented fully in later stage)
Route::get('/go/{company:slug}', function () {
    abort(501, 'Affiliate redirect will be implemented in the analytics stage.');
})->name('go');
