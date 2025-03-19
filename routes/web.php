<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::get("contacts", [ContactController::class, "show"])->name('contacts');

    Route::get("contacts/{contact}", [ContactController::class, "read"])->name('contacts.read');

    Route::post("contacts", [ContactController::class, "store"])->name('contacts.store');

    Route::put("contacts/{contact}", [ContactController::class, "update"])->name('contacts.update');

    Route::delete("contacts/{contact}", [ContactController::class, "destroy"])->name('contacts.destroy');

    Route::post('contacts/{contact}/call', [ContactController::class, 'call'])->name('contacts.call');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
