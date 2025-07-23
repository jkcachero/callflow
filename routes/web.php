<?php

use App\Http\Controllers\CallLogController;
use App\Http\Controllers\CallTicketController;
use App\Http\Controllers\CallTicketReassignController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('call-tickets', [CallTicketController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('call-tickets.index');

Route::get('call-tickets/{callTicket}', [CallTicketController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('call-tickets.show');

Route::put('call-tickets/{callTicket}', [CallTicketController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('call-tickets.update');

Route::patch('call-tickets/{callTicket}/reassign', CallTicketReassignController::class)
    ->middleware(['auth', 'verified'])
    ->name('call-tickets.reassign');

Route::post('call-tickets/{callTicket}/logs', [CallLogController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('call-tickets.logs.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('users', UserController::class)->except(['show']);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
