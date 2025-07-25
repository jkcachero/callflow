<?php

use App\Models\CallTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

test('supervisor can view agent tickets index', function () {
    $supervisor = User::factory()->create(['role' => 'supervisor']);
    $agent = User::factory()->create(['role' => 'agent']);

    CallTicket::factory()->count(3)->create(['assigned_user_id' => $agent->id]);

    actingAs($supervisor)
        ->get(route('agents.tickets.index', $agent))
        ->assertStatus(Response::HTTP_OK)
        ->assertInertia(fn ($page) =>
            $page->component('AgentTickets/Index')
                ->has('tickets.data', 3)
        );
});

test('admin can view agent tickets index', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $agent = User::factory()->create(['role' => 'agent']);

    CallTicket::factory()->count(2)->create(['assigned_user_id' => $agent->id]);

    actingAs($admin)
        ->get(route('agents.tickets.index', $agent))
        ->assertStatus(Response::HTTP_OK)
        ->assertInertia(fn ($page) =>
            $page->component('AgentTickets/Index')
                ->has('tickets.data', 2)
        );
});

test('agent cannot view agent tickets index', function () {
    $agent = User::factory()->create(['role' => 'agent']);
    $otherAgent = User::factory()->create(['role' => 'agent']);

    actingAs($agent)
        ->get(route('agents.tickets.index', $otherAgent))
        ->assertRedirect(route('call-tickets.index'))
        ->assertSessionHas('error', 'You are not authorized to view this page.');
});

test('supervisor can view a specific agent ticket', function () {
    $supervisor = User::factory()->create(['role' => 'supervisor']);
    $agent = User::factory()->create(['role' => 'agent']);
    $ticket = CallTicket::factory()->create(['assigned_user_id' => $agent->id]);

    actingAs($supervisor)
        ->get(route('agents.tickets.show', [$agent, $ticket]))
        ->assertStatus(Response::HTTP_OK)
        ->assertInertia(fn ($page) =>
            $page->component('AgentTickets/Show')
                ->where('callTicket.id', $ticket->id)
                ->where('agent.id', $agent->id)
        );
});

test('admin can view a specific agent ticket', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $agent = User::factory()->create(['role' => 'agent']);
    $ticket = CallTicket::factory()->create(['assigned_user_id' => $agent->id]);

    actingAs($admin)
        ->get(route('agents.tickets.show', [$agent, $ticket]))
        ->assertStatus(Response::HTTP_OK)
        ->assertInertia(fn ($page) =>
            $page->component('AgentTickets/Show')
                ->where('callTicket.id', $ticket->id)
                ->where('agent.id', $agent->id)
        );
});

test('agent cannot view another agent ticket', function () {
    $agent = User::factory()->create(['role' => 'agent']);
    $otherAgent = User::factory()->create(['role' => 'agent']);
    $ticket = CallTicket::factory()->create(['assigned_user_id' => $otherAgent->id]);

    actingAs($agent)
        ->get(route('agents.tickets.show', [$otherAgent, $ticket]))
        ->assertRedirect(route('call-tickets.index'))
        ->assertSessionHas('error', 'You are not authorized to view this page.');
});

test('show aborts 404 if ticket does not belong to agent', function () {
    $supervisor = User::factory()->create(['role' => 'supervisor']);
    $agent = User::factory()->create(['role' => 'agent']);
    $ticket = CallTicket::factory()->create(); // different assigned_user_id

    $this->actingAs($supervisor)
        ->get(route('agents.tickets.show', [$agent, $ticket]))
        ->assertStatus(Response::HTTP_NOT_FOUND);
});

