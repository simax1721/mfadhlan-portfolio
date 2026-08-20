<?php

use App\Http\Controllers\Api\BootstrapController;
use App\Http\Controllers\Api\CvController;
use App\Http\Controllers\Api\EducationEntryController;
use App\Http\Controllers\Api\ExperienceController;
use App\Http\Controllers\Api\OrganizationEntryController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\SkillController;
use Illuminate\Support\Facades\Route;

// Single combined payload the frontend loads on page open.
Route::get('/bootstrap', [BootstrapController::class, 'index']);

// Auto-generated CV, built from the same CMS data as the site.
Route::get('/cv', [CvController::class, 'show']);

// Individual resource endpoints (kept for direct/CMS-side use).
Route::get('/profile', [ProfileController::class, 'show']);
Route::get('/skills', [SkillController::class, 'index']);
Route::get('/experiences', [ExperienceController::class, 'index']);
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/education', [EducationEntryController::class, 'index']);
Route::get('/organizations', [OrganizationEntryController::class, 'index']);
