<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VaccinationRegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('register', [VaccinationRegistrationController::class, 'create'])->name('register');

Route::post('register', [VaccinationRegistrationController::class, 'store']);

Route::post('/check-vaccination-status', [VaccinationRegistrationController::class, 'checkVaccinationStatus'])->name('check-vaccination-status');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

 require __DIR__.'/auth.php';
