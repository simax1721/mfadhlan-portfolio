<?php

use Illuminate\Support\Facades\Route;

// This app is an API + Filament admin only — no public Blade frontend.
Route::get('/', fn () => redirect('/admin'));
