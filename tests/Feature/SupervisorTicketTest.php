<?php

use App\Models\CallTicket;
use App\Models\User;
use Illuminate\Http\Response;

use function Pest\Laravel\actingAs;

test('allows supervisor to reassign a ticket to another agent', function () {
    $supervisor = User::factory()->create(['role' => 'supervisor']);

    $agent1 = User::factory()->create(['role' => 'agent']);
    $agent2 = User::factory()->create(['role' => 'agent']);

    $callTicket = CallTicket::factory()->create(['assigned_user_id' => $agent1->id]);

    actingAs($supervisor)
        ->patch(route('call-tickets.reassign', $callTicket), ['assigned_user_id' => $agent2->id])
        ->assertStatus(Response::HTTP_OK);

    expect($callTicket->fresh()->assigned_user_id)->toBe($agent2->id);
});
