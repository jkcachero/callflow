<?php

use App\Http\Controllers\AgentTicketsController;
use App\Http\Controllers\CallLogController;
use App\Http\Controllers\CallTicketController;
use App\Http\Controllers\CallTicketReassignController;
use App\Http\Controllers\PhoneIntegrationController;
use App\Http\Controllers\ReportController;
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

Route::get('reports', [ReportController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('reports.index');

Route::get('agents/{agent}/tickets', [AgentTicketsController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('agents.tickets.index');

Route::get('agents/{agent}/tickets/{ticket}', [AgentTicketsController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('agents.tickets.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('telephone', [PhoneIntegrationController::class, 'index'])->name('telephone.index');
    Route::get('telephone/3cx', [PhoneIntegrationController::class, 'threeCX'])->name('telephone.3cx');
    Route::get('telephone/twilio', [PhoneIntegrationController::class, 'twilio'])->name('telephone.twilio');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
