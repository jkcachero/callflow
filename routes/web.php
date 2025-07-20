<?php

use App\Http\Controllers\Auth\RegisteredUserController;
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

Route::put('call-tickets/{callTicket}', [CallTicketController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('call-tickets.update');

Route::patch('call-tickets/{callTicket}/reassign', CallTicketReassignController::class)
    ->middleware(['auth', 'verified'])
    ->name('call-tickets.reassign');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('users', UserController::class)->except(['show']);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
