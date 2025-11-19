<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QueueTestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/queue-test', [QueueTestController::class, 'index'])->name('queue-test.index');
    Route::post('/queue-test', [QueueTestController::class, 'dispatch'])->name('queue-test.dispatch');
    Route::post('/queue-test/run-schedule', [QueueTestController::class, 'runSchedule'])->name('queue-test.run-schedule');
});

require __DIR__.'/auth.php';
