<?php

use App\Models\CallTicket;
use App\Models\User;
use Illuminate\Http\Response;

use function Pest\Laravel\actingAs;

test('allows supervisor to escalate a call ticket', function () {
    $supervisor = User::factory()->create(['role' => 'supervisor']);
    $callTicket = CallTicket::factory()->create([
        'status' => 'active'
    ]);

    actingAs($supervisor)
        ->put(route('call-tickets.update', $callTicket), ['status' => 'escalated'])
        ->assertStatus(Response::HTTP_FOUND);

    expect($callTicket->fresh()->status)->toBe('escalated');
});

it('prevents agent from escalating a call ticket', function () {
    $agent = User::factory()->create(['role' => 'agent']);
    $callTicket = CallTicket::factory()->create(['assigned_user_id' => $agent->id, 'status' => 'active']);

    actingAs($agent)
        ->put(route('call-tickets.update', $callTicket), ['status' => 'escalated'])
        ->assertStatus(Response::HTTP_FORBIDDEN);

    expect($callTicket->fresh()->status)->toBe('active');
});
