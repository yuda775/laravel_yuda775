<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HospitalsController;
use App\Http\Controllers\PatientsController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/hospitals/data', [HospitalsController::class, 'data'])->name('hospitals.data');
    Route::resource('hospitals', HospitalsController::class);

    Route::get('/patients/filter', [PatientsController::class, 'filter'])->name('patients.filter');
    Route::resource('patients', PatientsController::class);
});
