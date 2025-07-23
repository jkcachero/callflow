<?php

use App\Models\User;
use App\Models\CallTicket;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows validation error when adding empty note', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $callTicket = CallTicket::factory()->create();

    $this->actingAs($admin)
        ->post(route('call-tickets.logs.store', $callTicket), ['note' => ''])
        ->assertSessionHasErrors('note');
});

it('shows validation error when updating status with invalid value', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $callTicket = CallTicket::factory()->create();

    $this->actingAs($admin)
        ->put(route('call-tickets.update', $callTicket), ['status' => 'invalid_status'])
        ->assertSessionHasErrors('status');
});

