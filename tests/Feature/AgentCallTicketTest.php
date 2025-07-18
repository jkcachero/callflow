<?php

use App\Models\CallTicket;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\actingAs;

it('allows agent to update their assigned call status', function () {
    $agent = User::factory()->create(['role' => 'agent']);
    $callTicket = CallTicket::factory()->create([
        'assigned_user_id' => $agent->id,
        'status' => 'active'
    ]);

    actingAs($agent)
        ->put(route('call-tickets.update', $callTicket), ['status' => 'completed'])
        ->assertStatus(Response::HTTP_OK);

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
