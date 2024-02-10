<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PatientConsultationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientPhotoController;
use App\Http\Controllers\PatientShareController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

Route::get('/', function () {
    View::share('home', true);

    return inertia('Home');
});

Route::middleware(['auth:sanctum', 'user.activity.check'])->group(static function () {
    // Patients
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/patients/full-records', [PatientController::class, 'fullRecords'])->name('patients.full_records');
    Route::post('/patients/{patient}/report', [PatientController::class, 'saveReport'])->name('patients.save.report');
    Route::post('/patients/{patient}/comment', [PatientController::class, 'saveComment'])->name('patients.save.comment');
    Route::resource('patients', PatientController::class)->except('index');

    // Patient Photos
    Route::resource('patients.photos', PatientPhotoController::class);
    Route::post('/patients/{patient}/photos/{photo}/update-label', [PatientPhotoController::class, 'updateLabel'])->name('patients.photos.update-label');

    // Shared Patients
    Route::resource('patient-shares', PatientShareController::class);

    // Patient Consultation
    Route::resource('patient-consultations', PatientConsultationController::class);

    // Users
    Route::post('/users/{user}/toggle-activity', [UserController::class, 'toggleActivity'])->name('users.toggle_activity');
    Route::resource('users', UserController::class);

    // Roles
    Route::resource('roles', RoleController::class);
});

// Auth
Route::controller(LoginController::class)->group(static function () {
    Route::get('login', 'create')->name('login')->middleware('guest');
    Route::post('login', 'store')->middleware('guest');
    Route::delete('logout', 'destroy')->name('logout');
});

// Autocomplete routes
require_once __DIR__.'/web-parts/autocomplete.php';
