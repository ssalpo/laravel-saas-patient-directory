<?php

use App\Http\Controllers\Public\PatientController;
use Illuminate\Support\Facades\Route;

Route::get('/public/patients/check-result', [PatientController::class, 'checkResult'])->name('public.patients.check-result');

Route::get('/public/{hash}', [PatientController::class, 'show'])->name('public.patients.show');
