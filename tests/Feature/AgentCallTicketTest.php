<?php

use App\Models\CallTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

it('allows agent to update their assigned call status', function () {
    $agent = User::factory()->create(['role' => 'agent']);
    $callTicket = CallTicket::factory()->create([
        'assigned_user_id' => $agent->id,
        'status' => 'active'
    ]);

    actingAs($agent)
        ->put(route('call-tickets.update', $callTicket), ['status' => 'completed'])
        ->assertStatus(Response::HTTP_FOUND);

    expect($callTicket->fresh()->status)->toBe('completed');
});

it('prevents agent from updating unassigned call ticket', function () {
    $agent = User::factory()->create(['role' => 'agent']);
    $otherCallTicket = CallTicket::factory()->create([
        'assigned_user_id' => User::factory()->create([
            'role' => 'agent',
        ]),
    ]);

    actingAs($agent)
        ->put(route('call-tickets.update', $otherCallTicket), ['status' => 'completed'])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('allows assigned agent to add a note', function () {
    $agent = User::factory()->create(['role' => 'agent']);
    $callTicket = CallTicket::factory()->create(['assigned_user_id' => $agent->id]);

    $this->actingAs($agent)
        ->post(route('call-tickets.logs.store', $callTicket), ['note' => 'Test note'])
        ->assertRedirect();

    $this->assertDatabaseHas('call_logs', [
        'call_ticket_id' => $callTicket->id,
        'user_id' => $agent->id,
        'note' => 'Test note',
        'log_type' => 'note',
    ]);
});

it('prevents agent from adding note to unassigned call ticket', function () {
    $agent = User::factory()->create(['role' => 'agent']);
    $otherUser = User::factory()->create(['role' => 'agent']);
    $callTicket = CallTicket::factory()->create(['assigned_user_id' => $otherUser->id]);

    $this->actingAs($agent)
        ->post(route('call-tickets.logs.store', $callTicket), ['note' => 'Test note'])
        ->assertStatus(Response::HTTP_FORBIDDEN);
});

it('allows supervisor to add note to any call ticket', function () {
    $supervisor = User::factory()->create(['role' => 'supervisor']);
    $callTicket = CallTicket::factory()->create();

    $this->actingAs($supervisor)
        ->post(route('call-tickets.logs.store', $callTicket), ['note' => 'Supervisor note'])
        ->assertRedirect();

    $this->assertDatabaseHas('call_logs', [
        'call_ticket_id' => $callTicket->id,
        'user_id' => $supervisor->id,
        'note' => 'Supervisor note',
        'log_type' => 'note',
    ]);
});

it('allows admin to add note to any call ticket', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $callTicket = CallTicket::factory()->create();

    $this->actingAs($admin)
        ->post(route('call-tickets.logs.store', $callTicket), ['note' => 'Admin note'])
        ->assertRedirect();

    $this->assertDatabaseHas('call_logs', [
        'call_ticket_id' => $callTicket->id,
        'user_id' => $admin->id,
        'note' => 'Admin note',
        'log_type' => 'note',
    ]);
});

it('requires note field', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $callTicket = CallTicket::factory()->create();

    $this->actingAs($admin)
        ->post(route('call-tickets.logs.store', $callTicket), ['note' => ''])
        ->assertSessionHasErrors('note');
});
