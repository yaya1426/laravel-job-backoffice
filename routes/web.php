<?php


use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    // Dashboard Route
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Companies Management
    Route::resource('company', CompanyController::class);
    Route::post('/companies/{id}/restore', [CompanyController::class, 'restore'])->name('company.restore');

    // Applications Management
    Route::resource('application', ApplicationController::class);
    Route::post('application/{id}/restore', [ApplicationController::class, 'restore'])->name('application.restore');

    // Categories Management
    Route::resource('category', CategoryController::class);
    Route::post('category/{id}/restore', [CategoryController::class, 'restore'])->name('category.restore');

    // Job Vacancies Management
    Route::resource('job-vacancy', JobVacancyController::class);
    Route::post('job-vacancy/{id}/restore', [JobVacancyController::class, 'restore'])->name('job-vacancy.restore');

    // Users Management
    Route::resource('user', UserController::class);
    Route::post('user/{id}/restore', [UserController::class, 'restore'])->name('user.restore');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
