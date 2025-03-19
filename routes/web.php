<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get("contacts", [ContactController::class, "index"])->name('contacts.index');

    Route::get("contacts/{contact}", [ContactController::class, "show"])->name('contacts.show');

    Route::post("contacts", [ContactController::class, "store"])->name('contacts.store');

    Route::put("contacts/{contact}", [ContactController::class, "update"])->name('contacts.update');

    Route::delete("contacts/{contact}", [ContactController::class, "destroy"])->name('contacts.destroy');

    Route::post('contacts/{contact}/call', [ContactController::class, 'call'])->name('contacts.call');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
