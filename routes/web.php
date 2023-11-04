<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\MedicalClinicController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

Route::get('/', function() {
    View::share('home', true);

    return inertia('Home');
});

Route::get('/patients/check-result', [PatientController::class, 'checkResult'])->name('patient.check-result');

Route::middleware(['auth:sanctum', 'user.activity.check'])->group(static function () {
    // Patients
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/patients/all', [PatientController::class, 'all'])->name('patients.all');
    Route::get('/patients/full-records', [PatientController::class, 'fullRecords'])->name('patients.full_records');
    Route::get('/patients/daily-statistics', [PatientController::class, 'dailyStatistics'])->name('patients.daily-statistics');
    Route::post('/patients/{patient}/report', [PatientController::class, 'saveReport'])->name('patients.save.report');
    Route::post('/patients/{patient}/comment', [PatientController::class, 'saveComment'])->name('patients.save.comment');
    Route::get('/patients/{patient}/print', [PatientController::class, 'print'])->name('patients.print');
    Route::post('/patients/{patient}/submit', [PatientController::class, 'submit'])->name('patients.submit');
    Route::post('/patients/{patient}/edit-print-date', [PatientController::class, 'editPrintDate'])->name('patients.edit_print_date');
    Route::delete('/patients/{patient}/photos/{photo}/delete', [PatientController::class, 'deletePhoto'])->name('patients.photos.delete');
    Route::resource('patients', PatientController::class)->except('index');

    // Doctors
    Route::resource('doctors', DoctorController::class);
    Route::get('/doctors/{doctor}/patients', [DoctorController::class, 'patients'])->name('doctors.patients');

    // Doctors
    Route::resource('medical-clinics', MedicalClinicController::class);

    // Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{doctor}/show', [PaymentController::class, 'show'])->name('payments.show');

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


Route::get('/{hash}', [PatientController::class, 'publicShow'])->name('patients.public_show');
