<?php

use App\Http\Controllers\CallTicketController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::put('call-tickets/{callTicket}', [CallTicketController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('call-tickets.update');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
