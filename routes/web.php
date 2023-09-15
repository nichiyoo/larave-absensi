<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekananController;
use App\Http\Controllers\RekapAbsenController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::middleware('guest')->group(function () {
    Route::resource('rekanans', RekananController::class)->only(['index', 'store', 'show']);
    Route::post('rekanans/download', [RekananController::class, 'download'])->name('rekanans.download');
});

Route::middleware('auth')->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('absens', AbsenController::class)->only(['index', 'store', 'show']);
    Route::resource('rekap-absens', RekapAbsenController::class)->only(['update']);
});

require __DIR__ . '/auth.php';
