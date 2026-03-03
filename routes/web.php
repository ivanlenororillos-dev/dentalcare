<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DentalChartController;
use App\Http\Controllers\DentistController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ToothHistoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class , 'index'])->name('dashboard');

    Route::resource('clients', ClientController::class);
    Route::get('clients/{client}/chart', [DentalChartController::class , 'show'])->name('clients.chart');
    Route::get('clients/{client}/calibrate', [DentalChartController::class , 'calibrate'])->name('calibrate');
    Route::post('clients/{client}/calibrate', [DentalChartController::class , 'saveCalibration'])->name('calibrate.save');
    Route::get('clients/{client}/report/pdf', [ReportController::class , 'generatePdf'])->name('clients.report.pdf');

    Route::get('clients/{client}/teeth/{toothNumber}/history', [ToothHistoryController::class , 'index'])
        ->name('tooth-history.index');
    Route::post('clients/{client}/teeth/{toothNumber}/history', [ToothHistoryController::class , 'store'])
        ->name('tooth-history.store');
    Route::put('clients/{client}/teeth/{toothNumber}/history/{history}', [ToothHistoryController::class , 'update'])
        ->name('tooth-history.update');
    Route::delete('clients/{client}/teeth/{toothNumber}/history/{history}', [ToothHistoryController::class , 'destroy'])
        ->name('tooth-history.destroy');

    Route::resource('dentists', DentistController::class)->except(['show']);

    Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
