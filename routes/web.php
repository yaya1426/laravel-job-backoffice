<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Admin Only Routes
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    // User Management
    Route::resource('user', UserController::class);
    Route::post('user/{id}/restore', [UserController::class, 'restore'])->name('user.restore');

    // Category Management (Admin only)
    Route::resource('category', CategoryController::class);
    Route::post('category/{id}/restore', [CategoryController::class, 'restore'])->name('category.restore');
});

// Shared Routes (accessible by both admin and company owner)
Route::middleware(['auth', 'verified', 'admin.or.company'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Company Management
    Route::resource('company', CompanyController::class);
    Route::post('company/{id}/restore', [CompanyController::class, 'restore'])->name('company.restore');

    // Job Vacancy Management
    Route::resource('job-vacancy', JobVacancyController::class);
    Route::post('job-vacancy/{id}/restore', [JobVacancyController::class, 'restore'])->name('job-vacancy.restore');

    // Application Management
    Route::resource('application', ApplicationController::class);
    Route::post('application/{id}/restore', [ApplicationController::class, 'restore'])->name('application.restore');

    // Category View Only (for company owners)
    Route::get('category', [CategoryController::class, 'index'])->name('category.index');
});

// Company Owner Only Routes
Route::middleware(['auth', 'verified', 'company.owner'])->group(function () {
    // Company Profile
    Route::get('company/{company}', [CompanyController::class, 'show'])->name('company.show');
    Route::get('company/{company}/edit', [CompanyController::class, 'edit'])->name('company.edit');
    Route::put('company/{company}', [CompanyController::class, 'update'])->name('company.update');
});

// Shared Routes (accessible by both roles)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
