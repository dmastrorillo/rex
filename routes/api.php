<?php

use App\Http\Controllers\Api\ContactApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::prefix('v1')->group(function () {
    Route::get('/contacts', [ContactApiController::class, 'index'])->name('contacts.index');
    Route::post('/contacts', [ContactApiController::class, 'store'])->name('contacts.store');
    Route::get('/contacts/{contact}', [ContactApiController::class, 'show'])->name('contacts.show');
    Route::put('/contacts/{contact}', [ContactApiController::class, 'update'])->name('contacts.update');
    Route::delete('/contacts/{contact}', [ContactApiController::class, 'destroy'])->name('contacts.destroy');
    Route::post('/contacts/{contact}/call', [ContactApiController::class, 'initiateCall'])->name('contacts.initiateCall');
    Route::get('/calls/{call}', [ContactApiController::class, 'pollCall'])->name('calls.pollCall');
})->middleware('auth:sanctum');
