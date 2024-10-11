<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VaccinationRegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [VaccinationRegistrationController::class, 'index'])->name('home');
Route::get('register', [VaccinationRegistrationController::class, 'create'])->name('register');

Route::post('register', [VaccinationRegistrationController::class, 'store']);

Route::post('/check-vaccination-status', [VaccinationRegistrationController::class, 'checkVaccinationStatus'])->name('check-vaccination-status');
