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

it('allows supervisor to access reports page', function () {
    $supervisor = User::factory()->create(['role' => 'supervisor']);

    $response = $this->actingAs($supervisor)->get(route('reports.index'));

    $response->assertStatus(Response::HTTP_OK);
    $response->assertInertia(fn ($page) =>
        $page->component('Reports/Index')
    );
});

it('allows admin to access reports page', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('reports.index'));

    $response->assertStatus(Response::HTTP_OK);
    $response->assertInertia(fn ($page) =>
        $page->component('Reports/Index')
    );
});

it('prevents agent from accessing reports page', function () {
    $agent = User::factory()->create(['role' => 'agent']);

    $response = $this->actingAs($agent)->get(route('reports.index'));

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('prevents guest from accessing reports page', function () {
    $this->get(route('reports.index'))->assertRedirect(route('login'));
});
